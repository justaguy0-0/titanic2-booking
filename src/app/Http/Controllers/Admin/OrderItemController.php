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
    public function index(Request $request)
    {
        $query = OrderItem::with(['order.user', 'ticket.voyage', 'entertainment']);

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = [
            'id',
            'order_id',
            'item_type',
            'total_price',
            'created_at',
            'item_name',
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        if (in_array($sortField, ['id', 'order_id', 'item_type', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        elseif ($sortField === 'total_price') {
            $query->orderByRaw("(price * quantity) {$sortDirection}");
        }

        elseif ($sortField === 'item_name') {
            $query->select('order_items.*')
                ->leftJoin('tickets', 'order_items.ticket_id', '=', 'tickets.id')
                ->leftJoin('entertainments', 'order_items.entertainment_id', '=', 'entertainments.id')
                ->orderByRaw("
                  COALESCE(tickets.number, entertainments.name) {$sortDirection}
              ");
        }

        $orderItems = $query->paginate(15)->appends($request->query());

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
            'item_type' => 'required|in:ticket,entertainment',
            'ticket_id' => 'required_if:item_type,ticket|nullable|exists:tickets,id',
            'entertainment_id' => 'required_if:item_type,entertainment|nullable|exists:entertainments,id',
            'quantity' => 'required_if:item_type,entertainment|nullable|integer|min:1',
        ]);

        if ($validated['item_type'] === 'ticket') {
            $exists = OrderItem::where('order_id', $validated['order_id'])
                ->where('ticket_id', $validated['ticket_id'])
                ->exists();
            if ($exists) {
                return back()->withErrors(['ticket_id' => 'Этот билет уже добавлен в заказ.']);
            }
        }

        $price = 0;
        $ticket = null;

        if ($validated['item_type'] === 'ticket') {
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
            'ticket_id' => $validated['item_type'] === 'ticket' ? $validated['ticket_id'] : null,
            'entertainment_id' => $validated['item_type'] === 'entertainment' ? $validated['entertainment_id'] : null,
            'item_type' => $validated['item_type'],
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
            'item_type' => 'required|in:ticket,entertainment',
            'ticket_id' => 'required_if:item_type,ticket|nullable|exists:tickets,id',
            'entertainment_id' => 'required_if:item_type,entertainment|nullable|exists:entertainments,id',
            'quantity' => 'required_if:item_type,entertainment|nullable|integer|min:1',
        ]);

        $oldOrderId = $orderItem->order_id;
        $oldType = $orderItem->item_type;
        $oldTicket = $orderItem->ticket;

        $price = 0;
        $newTicket = null;

        if ($validated['item_type'] === 'ticket') {
            $newTicket = Ticket::findOrFail($validated['ticket_id']);
            if ($newTicket->status !== 'Доступно' && $newTicket->id !== $orderItem->ticket_id) {
                return back()->withErrors(['ticket_id' => "Билет {$newTicket->number} недоступен."]);
            }
            $price = $newTicket->price;

            if ($oldTicket && $oldTicket->id !== $newTicket->id) {
                $oldTicket->update(['status' => 'Доступно']);
            }
            $newTicket->update(['status' => 'Забронировано']);
        } else {
            $entertainment = Entertainment::findOrFail($validated['entertainment_id']);
            $price = $entertainment->price;

            if ($oldTicket) {
                $oldTicket->update(['status' => 'Доступно']);
            }
        }

        $orderItem->update([
            'order_id' => $validated['order_id'],
            'ticket_id' => $validated['item_type'] === 'ticket' ? $validated['ticket_id'] : null,
            'entertainment_id' => $validated['item_type'] === 'entertainment' ? $validated['entertainment_id'] : null,
            'item_type' => $validated['item_type'],
            'price' => $price,
            'quantity' => $validated['quantity'] ?? 1,
        ]);

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

            if ($orderItem->item_type === 'ticket' && $orderItem->ticket) {
                $orderItem->ticket->update(['status' => 'Доступно']);
            }

            $orderItem->delete();

            $order->refreshTotalPrice();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Билет успешно удален из заказа',
                    'order_id' => $order->id
                ]);
            }

            return redirect()
                ->route('admin.orders.edit', $order)
                ->with('success', 'Элемент удалён.');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка удаления: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Ошибка удаления: ' . $e->getMessage());
        }
    }
}
