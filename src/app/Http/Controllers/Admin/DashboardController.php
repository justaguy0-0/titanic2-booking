<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Voyage;
use App\Models\User;
use App\Models\Entertainment;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Временные метки для фильтрации
        $lastMonth = Carbon::now()->subMonth();
        $lastYear = Carbon::now()->subYear();

        // Ключевые метрики за последний месяц
        $totalOrders = Order::where('created_at', '>=', $lastMonth)->count();

        $revenue = Order::where('created_at', '>=', $lastMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $activeVoyages = Voyage::where('departure_date', '>=', Carbon::now())
            ->where('arrival_date', '>=', Carbon::now())
            ->count();

        // Дополнительные метрики
        $totalTicketsSold = Order::where('orders.created_at', '>=', $lastMonth)
            ->where('orders.status', '!=', 'cancelled')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.type', 'ticket')
            ->sum('order_items.quantity');

        $newUsersCount = User::where('created_at', '>=', $lastMonth)->count();
        $totalUsers = User::count();

        // Средний чек
        $averageOrderValue = Order::where('created_at', '>=', $lastMonth)
            ->where('status', '!=', 'cancelled')
            ->avg('total_price');

        // Недавние заказы (последние 10)
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {
                return (object) [
                    'id' => $order->id,
                    'user_name' => $order->user->name ?? 'Гость',
                    'amount' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d.m.Y H:i'),
                ];
            });

        // График продаж за последние 30 дней
        $salesData = Order::where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_price) as daily_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Форматируем данные для Chart.js
        $chartLabels = $salesData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d.m');
        })->toArray();

        $chartOrders = $salesData->pluck('orders_count')->toArray();
        $chartRevenue = $salesData->pluck('daily_revenue')->toArray();

        // Топ-5 популярных направлений
        $topDestinations = Voyage::select(
            'voyages.id',
            'dep.name as departure',
            'arr.name as arrival',
            DB::raw('COUNT(order_items.id) as bookings_count')
        )
            ->join('tickets', 'voyages.id', '=', 'tickets.voyages_id')
            ->join('order_items', 'tickets.id', '=', 'order_items.ticket_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('places as dep', 'voyages.departure_place_id', '=', 'dep.id')
            ->join('places as arr', 'voyages.arrival_place_id', '=', 'arr.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('voyages.id', 'dep.name', 'arr.name')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // Топ-5 развлечений
        $topEntertainments = Entertainment::select(
            'entertainments.id',
            'entertainments.name',
            'entertainments.price',
            DB::raw('SUM(order_items.quantity) as total_bookings'),
            DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
        )
            ->join('order_items', 'entertainments.id', '=', 'order_items.entertainment_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('entertainments.id', 'entertainments.name', 'entertainments.price')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // Статистика по статусам заказов
        $orderStatusStats = Order::select('status', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $lastMonth)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Предупреждения
        $alerts = [];

        // Просроченные платежи
        $overduePayments = Payment::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subDays(3))
            ->count();
        if ($overduePayments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Найдено {$overduePayments} просроченных платежей"
            ];
        }

        // Путешествия без билетов
        $voyagesWithoutTickets = Voyage::where('departure_date', '>', Carbon::now())
            ->whereDoesntHave('tickets')
            ->count();
        if ($voyagesWithoutTickets > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$voyagesWithoutTickets} предстоящих путешествий без билетов!"
            ];
        }

        // Низкий запас доступных билетов
        $lowStockVoyages = Voyage::where('departure_date', '>', Carbon::now())
            ->where('departure_date', '<', Carbon::now()->addDays(30))
            ->withCount(['tickets' => function ($query) {
                $query->where('status', 'Доступно');
            }])
            ->having('tickets_count', '<', 5)
            ->having('tickets_count', '>', 0)
            ->get();

        if ($lowStockVoyages->count() > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Мало доступных билетов на {$lowStockVoyages->count()} предстоящих путешествий"
            ];
        }

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'revenue',
            'activeVoyages',
            'totalTicketsSold',
            'newUsersCount',
            'totalUsers',
            'averageOrderValue',
            'recentOrders',
            'chartLabels',
            'chartOrders',
            'chartRevenue',
            'topDestinations',
            'topEntertainments',
            'orderStatusStats',
            'alerts'
        ));
    }
}
