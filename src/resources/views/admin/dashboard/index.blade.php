{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.admin')
@section('title', 'Дашборд')
@section('content')
    <div class="container mx-auto px-4 py-6 text-gray-800">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-ship mr-3 text-blue-600"></i> Дашборд Титаник 2
            </h1>
        </div>

        @if(count($alerts) > 0)
            <div class="mb-6 space-y-3">
                @foreach($alerts as $alert)
                    <div class="bg-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-50 border-l-4 border-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="{{ $alert['type'] === 'danger' ? 'fas fa-exclamation-triangle' : 'fas fa-exclamation-circle' }} text-2xl text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-700">{{ $alert['message'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('admin.orders.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg text-center transition flex items-center justify-center gap-3">
                <i class="fas fa-file-invoice"></i> Заказы
            </a>
            <a href="{{ route('admin.tickets.index') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg text-center transition flex items-center justify-center gap-3">
                <i class="fas fa-ticket-alt"></i> Билеты
            </a>
            <a href="{{ route('admin.voyages.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-4 px-6 rounded-lg text-center transition flex items-center justify-center gap-3">
                <i class="fas fa-route"></i> Рейсы
            </a>
            <a href="{{ route('admin.payments.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-4 px-6 rounded-lg text-center transition flex items-center justify-center gap-3">
                <i class="fas fa-credit-card"></i> Платежи
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold opacity-90">Заказы</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalOrders, 0, ',', ' ') }}</p>
                        <p class="text-xs opacity-80 mt-1">За последний месяц</p>
                    </div>
                    <div class="text-5xl opacity-30">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 shadow-lg rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold opacity-90">Выручка</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($revenue, 0, ',', ' ') }} ₽</p>
                        <p class="text-xs opacity-80 mt-1">За последний месяц</p>
                    </div>
                    <div class="text-5xl opacity-30">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold opacity-90">Активные путешествия</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($activeVoyages, 0, ',', ' ') }}</p>
                        <p class="text-xs opacity-80 mt-1">Запланировано</p>
                    </div>
                    <div class="text-5xl opacity-30">
                        <i class="fas fa-ship"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 shadow-lg rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold opacity-90">Продано билетов</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalTicketsSold, 0, ',', ' ') }}</p>
                        <p class="text-xs opacity-80 mt-1">За последний месяц</p>
                    </div>
                    <div class="text-5xl opacity-30">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-600">Средний чек</h3>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($averageOrderValue ?? 0, 0, ',', ' ') }} ₽</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-600">Новые пользователи</h3>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($newUsersCount, 0, ',', ' ') }}</p>
                <p class="text-xs text-gray-500 mt-1">Всего: {{ number_format($totalUsers, 0, ',', ' ') }}</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-600">Статусы заказов</h3>
                <div class="mt-2 space-y-1 text-sm">
                    @foreach($orderStatusStats as $status => $count)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ ucfirst($status) }}:</span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-line mr-2 text-green-600"></i> График продаж за последние 30 дней
            </h3>
            <canvas
                id="sales-chart"
                class="w-full h-96"
                data-labels="{{ json_encode($chartLabels) }}"
                data-orders="{{ json_encode($chartOrders) }}"
                data-revenue="{{ json_encode($chartRevenue) }}">
            </canvas>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-globe mr-2 text-blue-600"></i> Популярные направления
                </h3>
                @if($topDestinations->count() > 0)
                    <div class="space-y-3">
                        @foreach($topDestinations as $destination)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $destination->departure }} → {{ $destination->arrival }}</p>
                                    <p class="text-xs text-gray-500">{{ $destination->bookings_count }} бронирований</p>
                                </div>
                                <div class="text-blue-600 font-bold">{{ $destination->bookings_count }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет данных о бронированиях</p>
                @endif
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-theater-masks mr-2 text-purple-600"></i> Популярные развлечения
                </h3>
                @if($topEntertainments->count() > 0)
                    <div class="space-y-3">
                        @foreach($topEntertainments as $entertainment)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $entertainment->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $entertainment->total_bookings }} заказов • {{ number_format($entertainment->total_revenue, 0, ',', ' ') }} ₽</p>
                                </div>
                                <div class="text-purple-600 font-bold">{{ $entertainment->total_bookings }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет данных о развлечениях</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i> Недавние заказы
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->amount, 0, ',', ' ') }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">Просмотр</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-6xl mb-4 opacity-20">
                                    <i class="fas fa-water"></i>
                                </div>
                                <p>Нет недавних заказов</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/js/admin/dashboard-chart.js')
@endsection
