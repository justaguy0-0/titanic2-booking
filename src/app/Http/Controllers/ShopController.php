<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Models\Ticket;
use App\Models\Entertainment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    /**
     * Показать главную страницу магазина
     */
    public function index()
    {
        $voyages = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();

        $entertainments = Entertainment::all();

        return view('shop', compact('voyages', 'entertainments'));
    }

    public function showVoyage($voyageId)
    {
        $voyage = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->findOrFail($voyageId);

        $tickets = Ticket::where('voyages_id', $voyageId)
            ->where('status', 'Доступно')
            ->get();

        $availableCabinTypes = \App\Models\CabinType::whereHas('tickets', function ($query) use ($voyageId) {
                $query->where('voyages_id', $voyageId);
            })
            ->with(['tickets' => function ($query) use ($voyageId) {
                $query->where('voyages_id', $voyageId)
                      ->orderBy('number');
            }])
            ->orderBy('id')
            ->get();

        $entertainments = Entertainment::all();

        return view('shop.select-tickets', compact('voyage', 'tickets', 'availableCabinTypes', 'entertainments'));
    }

    
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'voyage_id' => 'required|exists:voyages,id',
            'tickets' => 'required|array|min:1',
            'tickets.*' => 'exists:tickets,id',
            'entertainments' => 'nullable|array',
            'entertainments.*.id' => 'exists:entertainments,id',
            'entertainments.*.quantity' => 'integer|min:0|max:10',
        ]);

        DB::beginTransaction();
        try {

            $tickets = Ticket::whereIn('id', $validated['tickets'])
                ->where('status', 'Доступно')
                ->lockForUpdate()
                ->get();

            if ($tickets->count() !== count($validated['tickets'])) {
                DB::rollBack();
                return back()->with('error', 'Некоторые билеты уже забронированы. Попробуйте выбрать другие.');
            }

            $totalPrice = $tickets->sum('price');
            $ticketCount = $tickets->count();


            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'ticket_count' => $ticketCount,
                'final_price' => $totalPrice, // Итоговая цена (до применения скидок при оплате)
                'status' => 'Новый',
            ]);


            foreach ($tickets as $ticket) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                    'item_type' => 'ticket',
                    'quantity' => 1,
                    'price' => $ticket->price,
                ]);
                $ticket->update(['status' => 'Забронировано']);
            }

            if (!empty($validated['entertainments'])) {
                foreach ($validated['entertainments'] as $entData) {
                    if (isset($entData['quantity']) && $entData['quantity'] > 0) {
                        $entertainment = Entertainment::find($entData['id']);
                        if ($entertainment) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'entertainment_id' => $entertainment->id,
                                'item_type' => 'entertainment',
                                'quantity' => $entData['quantity'],
                                'price' => $entertainment->price,
                            ]);
                            $totalPrice += $entertainment->price * $entData['quantity'];
                        }
                    }
                }

                $order->update(['total_price' => $totalPrice]);
            }

            DB::commit();

            session(['pending_order_id' => $order->id]);

            return redirect()->route('shop.payment');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при создании заказа: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при бронировании билетов. Попробуйте снова.');
        }
    }

   
    public function showPayment()
    {

        if (!session()->has('pending_order_id')) {
            return redirect()->route('shop')->with('error', 'Данные заказа не найдены.');
        }

        $orderId = session('pending_order_id');

        $order = Order::with([
            'orderItems.ticket.cabinType',
            'orderItems.entertainment'
        ])->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('shop')->with('error', 'Доступ запрещён.');
        }

        if ($order->status !== 'Новый') {
            session()->forget('pending_order_id');
            return redirect()->route('shop')->with('error', 'Заказ уже обработан или отменён.');
        }

        if ($order->created_at->addMinutes(10)->isPast()) {
            session()->forget('pending_order_id');
            return redirect()->route('shop')->with('error', 'Время бронирования истекло. Пожалуйста, выберите билеты заново.');
        }

        // Получаем информацию о рейсе (берём из первого билета)
        $firstTicket = $order->orderItems->where('item_type', 'ticket')->first()->ticket;
        $voyage = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->findOrFail($firstTicket->voyages_id);

        // Формируем список билетов
        $tickets = $order->orderItems
            ->where('item_type', 'ticket')
            ->pluck('ticket');

        // Формируем список развлечений
        $entertainmentItems = [];
        foreach ($order->orderItems->where('item_type', 'entertainment') as $item) {
            $entertainmentItems[] = [
                'entertainment' => $item->entertainment,
                'quantity' => $item->quantity,
                'subtotal' => $item->price * $item->quantity
            ];
        }

        $baseTotalPrice = $order->total_price;

        // РАСЧЕТ СКИДКИ ЛОЯЛЬНОСТИ
        $loyaltyInfo = $this->getLoyaltyInfo(Auth::user());
        $discountCalculation = $this->calculateOrderDiscount(Auth::user(), $baseTotalPrice);

        // Время до истечения бронирования
        $expiresAt = $order->created_at->addMinutes(10);

        return view('shop.payment', compact(
            'voyage',
            'tickets',
            'entertainmentItems',
            'baseTotalPrice',
            'loyaltyInfo',
            'discountCalculation',
            'order',
            'expiresAt'
        ));
    }

    /**
     * Обработать оплату существующего заказа
     */
    public function processPayment(Request $request)
    {
        Log::info('=== НАЧАЛО ОБРАБОТКИ ПЛАТЕЖА ===');
        Log::info('Request data:', $request->all());

        // Проверяем наличие ID заказа в сессии
        if (!session()->has('pending_order_id')) {
            Log::error('ID заказа не найден в сессии');
            return redirect()->route('shop')->with('error', 'Данные заказа не найдены.');
        }

        $orderId = session('pending_order_id');
        Log::info('ID заказа из сессии:', ['order_id' => $orderId]);

        // Валидация данных пассажиров и банковских реквизитов
        $validated = $request->validate([
            'passengers' => 'required|array',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.birth_date' => 'required|date|before:today',
            'passengers.*.passport_series' => 'required|string|max:10',
            'passengers.*.passport_number' => 'required|string|max:20',
            'passengers.*.citizenship' => 'required|string|max:100',
            'passengers.*.ticket_id' => 'required|exists:tickets,id',
            // Банковские данные
            'card_number' => 'required|string',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3',
            'card_holder' => 'required|string|min:3|max:100',
        ]);

        // Дополнительная валидация номера карты (должно быть 16 цифр после удаления пробелов)
        $cardNumber = str_replace(' ', '', $validated['card_number']);
        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            return back()->withInput()->withErrors(['card_number' => 'Номер карты должен содержать 16 цифр.']);
        }

        // Проверка формата срока действия (MM/YY)
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $validated['card_expiry'])) {
            return back()->withInput()->withErrors(['card_expiry' => 'Неверный формат срока действия. Используйте формат MM/ГГ.']);
        }

        // Проверка срока действия карты (не должен быть истекшим)
        [$month, $year] = explode('/', $validated['card_expiry']);
        $expiryDate = \Carbon\Carbon::createFromDate(2000 + (int)$year, (int)$month, 1)->endOfMonth();
        if ($expiryDate->isPast()) {
            return back()->withInput()->withErrors(['card_expiry' => 'Срок действия карты истек.']);
        }

        // Проверка CVV (должен содержать только цифры)
        if (!preg_match('/^\d{3}$/', $validated['card_cvv'])) {
            return back()->withInput()->withErrors(['card_cvv' => 'CVV код должен содержать только 3 цифры.']);
        }

        // Логируем успешную валидацию (без полных данных карты для безопасности)
        Log::info('Платежные данные валидированы', [
            'card_last4' => substr($cardNumber, -4),
            'card_holder' => $validated['card_holder'],
            'expiry' => $validated['card_expiry'],
        ]);

        DB::beginTransaction();
        try {
            // Получаем существующий заказ с блокировкой
            $order = Order::with('orderItems.ticket')
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            // Проверяем статус заказа
            if ($order->status !== 'Новый') {
                DB::rollBack();
                session()->forget('pending_order_id');
                return redirect()->route('shop')->with('error', 'Заказ уже обработан или отменён.');
            }

            // Проверяем, не истёк ли срок бронирования (10 минут)
            if ($order->created_at->addMinutes(10)->isPast()) {
                DB::rollBack();
                session()->forget('pending_order_id');
                return redirect()->route('shop')->with('error', 'Время бронирования истекло.');
            }

            // Получаем билеты из заказа
            $tickets = $order->orderItems
                ->where('item_type', 'ticket')
                ->pluck('ticket');

            // Рассчитываем общую стоимость с учетом скидок
            $totalPrice = 0;
            $ticketPrices = []; // Сохраняем цены с учетом скидок для каждого билета

            // ПРИМЕНЯЕМ СКИДКУ ЛОЯЛЬНОСТИ
            $loyaltyInfo = $this->getLoyaltyInfo(Auth::user());
            $loyaltyDiscount = $loyaltyInfo['discount'];

            foreach ($validated['passengers'] as $passengerData) {
                $ticket = $tickets->firstWhere('id', $passengerData['ticket_id']);
                if ($ticket) {
                    // Рассчитываем возраст
                    $birthDate = new \DateTime($passengerData['birth_date']);
                    $today = new \DateTime();
                    $age = $today->diff($birthDate)->y;

                    // Применяем скидку для детей до 12 лет
                    $childDiscountPercent = 0;
                    $basePrice = $ticket->price;

                    if ($age < 12) {
                        $childDiscountPercent = 20;
                        $basePrice = $ticket->price * (1 - $childDiscountPercent / 100);
                    }

                    // ПРИМЕНЯЕМ СКИДКУ ЛОЯЛЬНОСТИ
                    $finalPrice = $basePrice * (1 - $loyaltyDiscount / 100);

                    $ticketPrices[$passengerData['ticket_id']] = [
                        'original_price' => $ticket->price,
                        'final_price' => $finalPrice,
                        'child_discount' => $childDiscountPercent,
                        'loyalty_discount' => $loyaltyDiscount,
                        'age' => $age,
                        'passenger_data' => $passengerData,
                    ];

                    $totalPrice += $finalPrice;
                }
            }

            // Добавляем стоимость развлечений (они уже в заказе)
            foreach ($order->orderItems->where('item_type', 'entertainment') as $item) {
                $totalPrice += $item->price * $item->quantity;
            }

            // Симуляция оплаты: 70% успех, 30% отказ
            $paymentChance = rand(1, 100);
            if ($paymentChance > 70) {
                DB::rollBack();
                return back()->with('error', 'Оплата отклонена. Пожалуйста, попробуйте снова или используйте другой способ оплаты.');
            }

            // Обновляем заказ С УЧЕТОМ ЛОЯЛЬНОСТИ И СКИДОК
            $order->update([
                'total_price' => $totalPrice,
                'loyalty_discount_applied' => $loyaltyDiscount,
                'final_price' => $totalPrice,
                'status' => 'Оплачен',
            ]);

            // Определяем платежную систему рандомно (как на фронтенде)
            $paymentSystems = ['Visa', 'MasterCard', 'SBP', 'Tinkoff', 'Yandex'];
            $paymentProvider = $paymentSystems[array_rand($paymentSystems)];

            // Создаем запись о платеже
            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'provider' => $paymentProvider,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'Оплачен',
            ]);

            // Обновляем существующие OrderItem и добавляем данные пассажиров
            foreach ($ticketPrices as $ticketId => $priceData) {
                // Находим существующий OrderItem для этого билета
                $orderItem = $order->orderItems
                    ->where('item_type', 'ticket')
                    ->where('ticket_id', $ticketId)
                    ->first();

                if ($orderItem) {
                    // Обновляем цену с учётом всех скидок
                    $orderItem->update(['price' => $priceData['final_price']]);

                    // Создаем запись пассажира
                    Passenger::create([
                        'order_item_id' => $orderItem->id,
                        'first_name' => $priceData['passenger_data']['first_name'],
                        'last_name' => $priceData['passenger_data']['last_name'],
                        'birth_date' => $priceData['passenger_data']['birth_date'],
                        'passport_series' => $priceData['passenger_data']['passport_series'],
                        'passport_number' => $priceData['passenger_data']['passport_number'],
                        'citizenship' => $priceData['passenger_data']['citizenship'],
                        'age' => $priceData['age'],
                        'discount_percent' => $priceData['child_discount'],
                    ]);
                }
            }

            // Билеты уже в статусе "Забронировано", оставляем их без изменений

            // ОБНОВЛЯЕМ ЛОЯЛЬНОСТЬ ПОЛЬЗОВАТЕЛЯ
            $this->updateUserLoyalty(Auth::user());

            DB::commit();

            // Очищаем ID заказа из сессии
            session()->forget('pending_order_id');

            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно оплачен! Номер заказа: #' . $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== ОШИБКА ПРИ ОБРАБОТКЕ ПЛАТЕЖА ===');
            Log::error('Exception: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Ошибка при обработке оплаты: ' . $e->getMessage());
        }
    }

    /**
     * Рассчитывает уровень лояльности на основе количества билетов
     */
    private function calculateLoyaltyLevel(int $totalTickets): array
    {
        if ($totalTickets >= 10) {
            return [
                'level' => 3,
                'discount' => 20,
                'next_level_tickets' => null,
                'progress' => 100
            ];
        } elseif ($totalTickets >= 5) {
            $nextLevelTickets = 10 - $totalTickets;
            $progress = (($totalTickets - 5) / 5) * 100;
            
            return [
                'level' => 2,
                'discount' => 10,
                'next_level_tickets' => $nextLevelTickets,
                'progress' => min($progress, 100)
            ];
        } else {
            $nextLevelTickets = 5 - $totalTickets;
            $progress = ($totalTickets / 5) * 100;
            
            return [
                'level' => 1,
                'discount' => 0,
                'next_level_tickets' => $nextLevelTickets,
                'progress' => min($progress, 100)
            ];
        }
    }

    /**
     * Обновляет лояльность пользователя
     */
    private function updateUserLoyalty($user): void
    {
        // Считаем ТОЛЬКО оплаченные заказы
        $totalTickets = $user->orders()->where('status', 'Оплачен')->sum('ticket_count');
        
        $loyaltyData = $this->calculateLoyaltyLevel($totalTickets);
        
        $user->update([
            'total_tickets' => $totalTickets,
            'loyalty_level' => $loyaltyData['level'],
            'loyalty_discount' => $loyaltyData['discount']
        ]);
    }

    /**
     * Получает информацию о лояльности пользователя
     */
    private function getLoyaltyInfo($user): array
    {
        // ВАЖНО: всегда считаем на основе реальных заказов, а не сохраненного значения
        $totalTickets = $user->orders()->where('status', 'Оплачен')->sum('ticket_count');
        return $this->calculateLoyaltyLevel($totalTickets);
    }

    /**
     * Рассчитывает скидку для заказа
     */
    private function calculateOrderDiscount($user, float $totalPrice): array
    {
        $loyaltyInfo = $this->getLoyaltyInfo($user);
        $discountAmount = $totalPrice * ($loyaltyInfo['discount'] / 100);
        $finalPrice = $totalPrice - $discountAmount;

        return [
            'base_total' => $totalPrice,
            'discount_percent' => $loyaltyInfo['discount'],
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'loyalty_info' => $loyaltyInfo
        ];
    }
}