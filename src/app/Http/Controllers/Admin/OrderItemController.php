<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::with(['order.user', 'ticket.voyage'])->paginate(10);
        return view('admin.order-items.index', compact('orderItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::with('user')->get();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        return view('admin.order-items.create', compact('orders', 'tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'ticket_id' => 'required|exists:tickets,id|unique:order_items,ticket_id',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);
        if ($ticket->status !== 'Доступно') {
            return back()->withErrors(['ticket_id' => "Билет {$ticket->number} недоступен."])->withInput();
        }

        OrderItem::create($validated);
        $ticket->update(['status' => 'Забронировано']);

        // Обновляем total_price заказа
        $order = Order::findOrFail($validated['order_id']);
        $totalPrice = $order->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
        $order->update(['total_price' => $totalPrice ?: 0]);

        return redirect()->route('admin.order-items.index')->with('success', 'Элемент заказа успешно добавлен.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        $orders = Order::with('user')->get();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get()->merge([$orderItem->ticket]);
        return view('admin.order-items.edit', compact('orderItem', 'orders', 'tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'ticket_id' => 'required|exists:tickets,id|unique:order_items,ticket_id,' . $orderItem->id,
        ]);

        $oldTicket = $orderItem->ticket;
        $oldOrderId = $orderItem->order_id;

        if ($oldTicket->id != $validated['ticket_id']) {
            $newTicket = Ticket::findOrFail($validated['ticket_id']);
            if ($newTicket->status !== 'Доступно') {
                return back()->withErrors(['ticket_id' => "Билет {$newTicket->number} недоступен."])->withInput();
            }
            $oldTicket->update(['status' => 'Доступно']);
            $newTicket->update(['status' => 'Забронировано']);
        }

        $orderItem->update($validated);

        // Обновляем total_price для старого и нового заказов (если order_id изменился)
        if ($oldOrderId != $validated['order_id']) {
            $oldOrder = Order::findOrFail($oldOrderId);
            $oldTotalPrice = $oldOrder->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
            $oldOrder->update(['total_price' => $oldTotalPrice ?: 0]);
        }

        $order = Order::findOrFail($validated['order_id']);
        $totalPrice = $order->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
        $order->update(['total_price' => $totalPrice ?: 0]);

        return redirect()->route('admin.order-items.index')->with('success', 'Элемент заказа успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        try {
            $order = $orderItem->order;
            $ticket = $orderItem->ticket;
            $ticket->update(['status' => 'Доступно']);
            $orderItem->delete();

            // Обновляем total_price заказа
            $totalPrice = $order->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
            $order->update(['total_price' => $totalPrice ?: 0]);

            return redirect()->route('admin.order-items.index')->with('success', 'Элемент заказа успешно удалён.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка удаления элемента заказа.');
        }
    }
}
