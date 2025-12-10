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
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.ticket.voyage']);

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = [
            'id',
            'total_price',
            'status',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        if (in_array($sortField, ['id', 'total_price', 'status', 'created_at', 'updated_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        if ($sortField === 'user_email') {
            $query->join('users', 'orders.user_id', '=', 'users.id')
                ->orderBy('users.email', $sortDirection)
                ->select('orders.*');
        }

        $orders = $query->paginate(15)->appends($request->query());

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
            'final_price' => $validated['total_price'],
        ]);

        if (!empty($validated['tickets'])) {
            foreach ($validated['tickets'] as $ticketId) {
                $ticket = Ticket::findOrFail($ticketId);
                if ($ticket->status !== 'Доступно') continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticketId,
                    'item_type' => 'ticket',
                    'price' => $ticket->price,
                    'quantity' => 1,
                ]);
                $ticket->update(['status' => 'Забронировано']);
            }
        }

        if (!empty($validated['entertainments'])) {
            foreach ($validated['entertainments'] as $item) {
                $ent = Entertainment::findOrFail($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'entertainment_id' => $ent->id,
                    'item_type' => 'entertainment',
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

        $existingEntertainments = $order->orderItems
            ->where('item_type', 'entertainment')
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
            'existing_tickets' => 'nullable|array',
            'existing_tickets.*' => 'exists:tickets,id',
            'tickets' => 'nullable|array',
            'tickets.*' => 'exists:tickets,id',
            'entertainments' => 'nullable|array',
            'entertainments.*.id' => 'exists:entertainments,id',
            'entertainments.*.quantity' => 'integer|min:1',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
            'total_price' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            $order->update([
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'total_price' => $validated['total_price'],
                'final_price' => $validated['final_price'],
            ]);

            $allTicketIds = array_merge(
                $validated['existing_tickets'] ?? [],
                $validated['tickets'] ?? []
            );

            $order->orderItems()
                ->where('item_type', 'ticket')
                ->whereNotIn('ticket_id', $allTicketIds)
                ->each(function ($item) {
                    if ($item->ticket) {
                        $item->ticket->update(['status' => 'Доступно']);
                    }
                    $item->delete();
                });

            if (!empty($validated['tickets'])) {
                foreach ($validated['tickets'] as $ticketId) {
                    $ticket = Ticket::find($ticketId);
                    if (!$ticket || $ticket->status !== 'Доступно') {
                        continue;
                    }

                    $exists = $order->orderItems()
                        ->where('item_type', 'ticket')
                        ->where('ticket_id', $ticketId)
                        ->exists();

                    if (!$exists) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'ticket_id' => $ticketId,
                            'item_type' => 'ticket',
                            'price' => $ticket->price,
                            'quantity' => 1,
                        ]);
                        $ticket->update(['status' => 'Забронировано']);
                    }
                }
            }

            $order->orderItems()
                ->where('item_type', 'entertainment')
                ->delete();

            if (!empty($validated['entertainments'])) {
                foreach ($validated['entertainments'] as $entItem) {
                    if (empty($entItem['id'])) {
                        continue;
                    }

                    $ent = Entertainment::find($entItem['id']);
                    if (!$ent) {
                        continue;
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'entertainment_id' => $ent->id,
                        'item_type' => 'entertainment',
                        'price' => $ent->price,
                        'quantity' => $entItem['quantity'] ?? 1,
                    ]);
                }
            }

            \DB::commit();

            return redirect()->route('admin.orders.edit', $order)
                ->with('success', 'Заказ успешно обновлён.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error updating order: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Произошла ошибка при обновлении заказа: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Order $order)
    {
        try {
            foreach ($order->orderItems as $item) {
                if ($item->item_type === 'ticket' && $item->ticket) {
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
