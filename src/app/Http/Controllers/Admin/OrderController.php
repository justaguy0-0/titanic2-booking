<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use App\Models\OrderItem;
use App\Models\Entertainment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.ticket.voyage'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        $entertainments = Entertainment::all();
        return view('admin.orders.create', compact('users', 'tickets', 'entertainments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tickets' => 'nullable|array',
            'tickets.*' => 'exists:tickets,id',
            'entertainments' => 'nullable|array',
            'entertainments.*.id' => 'exists:entertainments,id',
            'entertainments.*.quantity' => 'integer|min:1',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
        ]);

        // === БИЛЕТЫ ===
        if (!empty($validated['tickets'])) {
            foreach ($validated['tickets'] as $ticketId) {
                $ticket = Ticket::findOrFail($ticketId);
                if ($ticket->status !== 'Доступно') continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticketId,
                    'type' => 'ticket',
                    'price' => $ticket->price,
                    'quantity' => 1,
                ]);
                $ticket->update(['status' => 'Забронировано']);
            }
        }

        // === РАЗВЛЕЧЕНИЯ ===
        if (!empty($validated['entertainments'])) {
            foreach ($validated['entertainments'] as $item) {
                $ent = Entertainment::findOrFail($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'entertainment_id' => $ent->id,
                    'type' => 'entertainment',
                    'price' => $ent->price,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Заказ создан.');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.ticket.voyage', 'orderItems.entertainment']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::all();
        $tickets = Ticket::where('status', 'Доступно')->with('voyage')->get();
        $entertainments = Entertainment::all();

        // ← КЛЮЧЕВОЕ: Подготовка данных для чекбоксов
        $existingEntertainments = $order->orderItems
            ->where('type', 'entertainment')
            ->pluck('quantity', 'entertainment_id')
            ->toArray();

        return view('admin.orders.edit', compact(
            'order',
            'users',
            'tickets',
            'entertainments',
            'existingEntertainments'
        ));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tickets' => 'nullable|array',
            'tickets.*' => 'exists:tickets,id',
            'entertainments' => 'nullable|array',
            'entertainments.*.id' => 'exists:entertainments,id',
            'entertainments.*.quantity' => 'integer|min:1',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
            'total_price' => 'required|numeric|min:0',
        ]);

        // === ОБНОВЛЯЕМ ЗАКАЗ ===
        $order->update([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'], // ← из формы
        ]);

        // === БИЛЕТЫ ===
        $newTicketIds = $validated['tickets'] ?? [];

        $order->orderItems()
            ->where('type', 'ticket')
            ->whereNotIn('ticket_id', $newTicketIds)
            ->each(function ($item) {
                if ($item->ticket) {
                    $item->ticket->update(['status' => 'Доступно']);
                }
                $item->delete();
            });

        foreach ($newTicketIds as $ticketId) {
            $ticket = Ticket::find($ticketId);
            if (!$ticket || $ticket->status !== 'Доступно') continue;

            $exists = $order->orderItems()
                ->where('type', 'ticket')
                ->where('ticket_id', $ticketId)
                ->exists();

            if (!$exists) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticketId,
                    'type' => 'ticket',
                    'price' => $ticket->price,
                    'quantity' => 1,
                ]);
                $ticket->update(['status' => 'Забронировано']);
            }
        }

        // === РАЗВЛЕЧЕНИЯ ===
        $newEntIds = collect($validated['entertainments'] ?? [])->pluck('id')->toArray();

        $order->orderItems()
            ->where('type', 'entertainment')
            ->whereNotIn('entertainment_id', $newEntIds)
            ->delete();

        foreach ($validated['entertainments'] ?? [] as $item) {
            $ent = Entertainment::findOrFail($item['id']);
            OrderItem::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'entertainment_id' => $ent->id,
                ],
                [
                    'type' => 'entertainment',
                    'price' => $ent->price,
                    'quantity' => $item['quantity'] ?? 1,
                ]
            );
        }

        // ← УБРАЛ refreshTotalPrice() — сумма из формы!
        return redirect()->route('admin.orders.edit', $order)->with('success', 'Заказ обновлён.');
    }

    public function destroy(Order $order)
    {
        try {
            foreach ($order->orderItems as $item) {
                if ($item->type === 'ticket' && $item->ticket) {
                    $item->ticket->update(['status' => 'Доступно']);
                }
                $item->delete();
            }
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Заказ удалён.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка удаления.');
        }
    }
}
