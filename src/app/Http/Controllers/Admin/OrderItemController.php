<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Entertainment;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['order.user', 'ticket.voyage', 'entertainment'])->paginate(10);
        return view('admin.order-items.index', compact('orderItems'));
    }

    public function create()
    {
        $orders = Order::with('user')->get();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        $entertainments = Entertainment::all();
        return view('admin.order-items.create', compact('orders', 'tickets', 'entertainments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:ticket,entertainment',
            'ticket_id' => 'required_if:type,ticket|nullable|exists:tickets,id',
            'entertainment_id' => 'required_if:type,entertainment|nullable|exists:entertainments,id',
            'quantity' => 'required_if:type,entertainment|nullable|integer|min:1',
        ]);

        // Проверка: билет уже в заказе?
        if ($validated['type'] === 'ticket') {
            $exists = OrderItem::where('order_id', $validated['order_id'])
                ->where('ticket_id', $validated['ticket_id'])
                ->exists();
            if ($exists) {
                return back()->withErrors(['ticket_id' => 'Этот билет уже добавлен в заказ.']);
            }
        }

        $price = 0;
        $ticket = null;

        if ($validated['type'] === 'ticket') {
            $ticket = Ticket::findOrFail($validated['ticket_id']);
            if ($ticket->status !== 'Доступно') {
                return back()->withErrors(['ticket_id' => "Билет {$ticket->number} недоступен."]);
            }
            $price = $ticket->price;
            $ticket->update(['status' => 'Забронировано']);
        } else {
            $entertainment = Entertainment::findOrFail($validated['entertainment_id']);
            $price = $entertainment->price;
        }

        OrderItem::create([
            'order_id' => $validated['order_id'],
            'ticket_id' => $validated['type'] === 'ticket' ? $validated['ticket_id'] : null,
            'entertainment_id' => $validated['type'] === 'entertainment' ? $validated['entertainment_id'] : null,
            'type' => $validated['type'],
            'price' => $price,
            'quantity' => $validated['quantity'] ?? 1,
        ]);

        Order::find($validated['order_id'])->refreshTotalPrice();

        return redirect()->route('admin.order-items.index')->with('success', 'Элемент добавлен.');
    }

    public function edit(OrderItem $orderItem)
    {
        $orders = Order::with('user')->get();
        $tickets = Ticket::where('status', 'Доступно')
            ->with('voyage')
            ->get()
            ->merge($orderItem->ticket ? [$orderItem->ticket] : []);
        $entertainments = Entertainment::all();

        return view('admin.order-items.edit', compact('orderItem', 'orders', 'tickets', 'entertainments'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:ticket,entertainment',
            'ticket_id' => 'required_if:type,ticket|nullable|exists:tickets,id',
            'entertainment_id' => 'required_if:type,entertainment|nullable|exists:entertainments,id',
            'quantity' => 'required_if:type,entertainment|nullable|integer|min:1',
        ]);

        $oldOrderId = $orderItem->order_id;
        $oldType = $orderItem->type;
        $oldTicket = $orderItem->ticket;

        $price = 0;
        $newTicket = null;

        if ($validated['type'] === 'ticket') {
            $newTicket = Ticket::findOrFail($validated['ticket_id']);
            if ($newTicket->status !== 'Доступно' && $newTicket->id !== $orderItem->ticket_id) {
                return back()->withErrors(['ticket_id' => "Билет {$newTicket->number} недоступен."]);
            }
            $price = $newTicket->price;

            // Освобождаем старый билет, если он был и отличается
            if ($oldTicket && $oldTicket->id !== $newTicket->id) {
                $oldTicket->update(['status' => 'Доступно']);
            }
            $newTicket->update(['status' => 'Забронировано']);
        } else {
            $entertainment = Entertainment::findOrFail($validated['entertainment_id']);
            $price = $entertainment->price;

            // Освобождаем билет, если был
            if ($oldTicket) {
                $oldTicket->update(['status' => 'Доступно']);
            }
        }

        $orderItem->update([
            'order_id' => $validated['order_id'],
            'ticket_id' => $validated['type'] === 'ticket' ? $validated['ticket_id'] : null,
            'entertainment_id' => $validated['type'] === 'entertainment' ? $validated['entertainment_id'] : null,
            'type' => $validated['type'],
            'price' => $price,
            'quantity' => $validated['quantity'] ?? 1,
        ]);

        // Пересчёт для старого и нового заказа
        if ($oldOrderId != $validated['order_id']) {
            Order::find($oldOrderId)?->refreshTotalPrice();
        }
        Order::find($validated['order_id'])?->refreshTotalPrice();

        return redirect()->route('admin.order-items.index')->with('success', 'Элемент обновлён.');
    }

    public function destroy(OrderItem $orderItem)
    {
        try {
            $order = $orderItem->order;

            // Возвращаем билет только если это билет
            if ($orderItem->type === 'ticket' && $orderItem->ticket) {
                $orderItem->ticket->update(['status' => 'Доступно']);
            }

            $orderItem->delete();

            // Правильный пересчёт: price * quantity для всех типов
            $order->refreshTotalPrice();

            return redirect()
                ->route('admin.orders.edit', $order)
                ->with('success', 'Элемент удалён.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка удаления: ' . $e->getMessage());
        }
    }
}
