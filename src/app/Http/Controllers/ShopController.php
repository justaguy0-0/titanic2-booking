<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Models\Ticket;
use App\Models\Entertainment;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * Показать страницу выбора билетов для рейса
     */
    public function showVoyage($voyageId)
    {
        $voyage = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->findOrFail($voyageId);

        // Получаем ТОЛЬКО доступные билеты для скрытых чекбоксов (для отправки формы)
        $tickets = Ticket::where('voyages_id', $voyageId)
            ->where('status', 'Доступно')
            ->get();

        // Группируем ВСЕ билеты (включая забронированные) по типам кают
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

    /**
     * Подготовка к покупке (сохранение данных в сессию)
     */
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

        // Проверяем доступность билетов
        $tickets = Ticket::whereIn('id', $validated['tickets'])
            ->where('status', 'Доступно')
            ->get();

        if ($tickets->count() !== count($validated['tickets'])) {
            return back()->with('error', 'Некоторые билеты уже забронированы. Попробуйте выбрать другие.');
        }

        // Сохраняем данные заказа в сессию
        session([
            'order_data' => [
                'voyage_id' => $validated['voyage_id'],
                'tickets' => $validated['tickets'],
                'entertainments' => $validated['entertainments'] ?? [],
            ]
        ]);

        return redirect()->route('shop.payment');
    }

    /**
     * Показать страницу оплаты
     */
    public function showPayment()
    {
        // Проверяем наличие данных заказа в сессии
        if (!session()->has('order_data')) {
            return redirect()->route('shop')->with('error', 'Данные заказа не найдены.');
        }

        $orderData = session('order_data');

        // Получаем информацию о рейсе
        $voyage = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->findOrFail($orderData['voyage_id']);

        // Получаем выбранные билеты
        $tickets = Ticket::whereIn('id', $orderData['tickets'])
            ->where('status', 'Доступно')
            ->get();

        // Если билеты недоступны, перенаправляем обратно
        if ($tickets->count() !== count($orderData['tickets'])) {
            session()->forget('order_data');
            return redirect()->route('shop')->with('error', 'Некоторые билеты стали недоступны.');
        }

        // Рассчитываем стоимость билетов
        $totalPrice = $tickets->sum('price');

        // Получаем развлечения
        $entertainmentItems = [];
        if (!empty($orderData['entertainments'])) {
            foreach ($orderData['entertainments'] as $entData) {
                if (isset($entData['quantity']) && $entData['quantity'] > 0) {
                    $entertainment = Entertainment::find($entData['id']);
                    if ($entertainment) {
                        $quantity = $entData['quantity'];
                        $totalPrice += $entertainment->price * $quantity;
                        $entertainmentItems[] = [
                            'entertainment' => $entertainment,
                            'quantity' => $quantity,
                            'subtotal' => $entertainment->price * $quantity
                        ];
                    }
                }
            }
        }

        return view('shop.payment', compact('voyage', 'tickets', 'entertainmentItems', 'totalPrice'));
    }

    /**
     * Обработать оплату и создать заказ
     */
    public function processPayment(Request $request)
    {
        // Проверяем наличие данных заказа в сессии
        if (!session()->has('order_data')) {
            return redirect()->route('shop')->with('error', 'Данные заказа не найдены.');
        }

        $orderData = session('order_data');

        DB::beginTransaction();
        try {
            // Получаем билеты с блокировкой
            $tickets = Ticket::whereIn('id', $orderData['tickets'])
                ->where('status', 'Доступно')
                ->lockForUpdate()
                ->get();

            if ($tickets->count() !== count($orderData['tickets'])) {
                DB::rollBack();
                session()->forget('order_data');
                return redirect()->route('shop')->with('error', 'Некоторые билеты уже забронированы.');
            }

            // Рассчитываем общую стоимость
            $totalPrice = $tickets->sum('price');

            // Добавляем развлечения
            $entertainmentItems = [];
            if (!empty($orderData['entertainments'])) {
                foreach ($orderData['entertainments'] as $entData) {
                    if (isset($entData['quantity']) && $entData['quantity'] > 0) {
                        $entertainment = Entertainment::find($entData['id']);
                        if ($entertainment) {
                            $quantity = $entData['quantity'];
                            $totalPrice += $entertainment->price * $quantity;
                            $entertainmentItems[] = [
                                'entertainment' => $entertainment,
                                'quantity' => $quantity
                            ];
                        }
                    }
                }
            }

            // Симуляция оплаты: 70% шанс успеха, 30% шанс неудачи
            $paymentChance = rand(1, 100);
            if ($paymentChance > 70) {
                DB::rollBack();
                return back()->with('error', 'Оплата отклонена. Пожалуйста, попробуйте снова или используйте другой способ оплаты.');
            }

            // Создаём заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'Оплачен',
            ]);

            // Добавляем билеты в заказ
            foreach ($tickets as $ticket) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                    'entertainment_id' => null,
                    'item_type' => 'ticket',
                    'quantity' => 1,
                    'price' => $ticket->price,
                ]);

                $ticket->update(['status' => 'Забронировано']);
            }

            // Добавляем развлечения в заказ
            foreach ($entertainmentItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => null,
                    'entertainment_id' => $item['entertainment']->id,
                    'item_type' => 'entertainment',
                    'quantity' => $item['quantity'],
                    'price' => $item['entertainment']->price,
                ]);
            }

            DB::commit();

            // Очищаем данные заказа из сессии
            session()->forget('order_data');

            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно оплачен! Номер заказа: #' . $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при обработке оплаты: ' . $e->getMessage());
        }
    }
}