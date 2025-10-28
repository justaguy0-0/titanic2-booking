<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.ticket'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        return view('admin.orders.create', compact('users', 'tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tickets' => 'required|array|min:1',
            'tickets.*' => 'exists:tickets,id',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
        ]);

        // Создаём заказ
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_price' => 0, // Будет обновлено
        ]);

        // Добавляем билеты
        foreach ($validated['tickets'] as $ticketId) {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->status !== 'Доступно') {
                return back()->withErrors(['tickets' => "Билет {$ticket->number} недоступен."])->withInput();
            }
            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $ticketId,
            ]);
            $ticket->update(['status' => 'Забронировано']);
        }

        // Обновляем total_price
        $totalPrice = $order->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
        $order->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.ticket.voyage']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $users = User::all();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        return view('admin.orders.edit', compact('order', 'users', 'tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tickets' => 'nullable|array',
            'tickets.*' => 'exists:tickets,id',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
        ]);

        // Обновляем заказ
        $order->update([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ]);

        // Добавляем новые билеты
        if (!empty($validated['tickets'])) {
            foreach ($validated['tickets'] as $ticketId) {
                $ticket = Ticket::findOrFail($ticketId);
                if ($ticket->status !== 'Доступно') {
                    return back()->withErrors(['tickets' => "Билет {$ticket->number} недоступен."])->withInput();
                }
                OrderItem::firstOrCreate([
                    'order_id' => $order->id,
                    'ticket_id' => $ticketId,
                ]);
                $ticket->update(['status' => 'Забронировано']);
            }
        }

        // Обновляем total_price
        $totalPrice = $order->orderItems()->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')->sum('tickets.price');
        $order->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            // Обновляем статус билетов на Доступно
            foreach ($order->orderItems as $item) {
                $item->ticket->update(['status' => 'Доступно']);
                $item->delete();
            }
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удалён.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка удаления: возможно, связанные элементы или платежи.');
        }
    }
}
