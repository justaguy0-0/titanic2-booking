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
        $voyages = Voyage::with(['placeDeparture', 'icebergArrival'])
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
        $voyage = Voyage::with(['placeDeparture', 'icebergArrival'])->findOrFail($voyageId);
        
        $tickets = Ticket::where('voyages_id', $voyageId)
            ->where('status', 'Доступно')
            ->get();

        $entertainments = Entertainment::all();

        return view('shop.voyage', compact('voyage', 'tickets', 'entertainments'));
    }

    /**
     * Оформить покупку
     */
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'voyage_id' => 'required|exists:voyages,id',
            'tickets' => 'required|array|min:1',
            'tickets.*' => 'exists:tickets,id',
            'entertainments' => 'nullable|array',
            'entertainments.*.id' => 'exists:entertainments,id',
            'entertainments.*.quantity' => 'integer|min:1|max:10',
        ]);

        DB::beginTransaction();
        try {
            // Получаем билеты
            $tickets = Ticket::whereIn('id', $validated['tickets'])
                ->where('status', 'Доступно')
                ->lockForUpdate()
                ->get();

            if ($tickets->count() !== count($validated['tickets'])) {
                return back()->with('error', 'Некоторые билеты уже забронированы. Попробуйте выбрать другие.');
            }

            // Рассчитываем общую стоимость
            $totalPrice = $tickets->sum('price');
            
            // Добавляем развлечения
            $entertainmentItems = [];
            if (!empty($validated['entertainments'])) {
                foreach ($validated['entertainments'] as $entData) {
                    $entertainment = Entertainment::find($entData['id']);
                    if ($entertainment) {
                        $quantity = $entData['quantity'] ?? 1;
                        $totalPrice += $entertainment->price * $quantity;
                        $entertainmentItems[] = [
                            'entertainment' => $entertainment,
                            'quantity' => $quantity
                        ];
                    }
                }
            }

            // Создаём заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'Новый',
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

                // Обновляем статус билета
                $ticket->update(['status' => 'Забронирован']);
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

            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно оформлен! Номер заказа: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }
}