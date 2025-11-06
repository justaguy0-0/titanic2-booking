{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.admin')

@section('title', 'Дашборд')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Дашборд</h1>
        <p class="text-gray-600 mt-2">Обзор ключевых метрик и статистики</p>
    </div>

    {{-- Информация о том, что должно быть в дашборде --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Что должно быть в дашборде:</h2>
        <ul class="list-disc pl-6 text-gray-700 space-y-2">
            <li><strong>Ключевые метрики:</strong> Общее количество заказов, платежей, билетов и путешествий за последний месяц/год.</li>
            <li><strong>Графики и диаграммы:</strong> График продаж по дням, распределение по местам отправления/прибытия, топ-развлечения.</li>
            <li><strong>Недавняя активность:</strong> Список последних заказов, платежей или элементов заказов с ссылками на детали.</li>
            <li><strong>Предупреждения:</strong> Уведомления о просроченных платежах, низком количестве билетов или других проблемах.</li>
            <li><strong>Быстрые ссылки:</strong> Кнопки для перехода к другим разделам (заказы, билеты и т.д.).</li>
            <li><strong>Статистика пользователей:</strong> Количество активных пользователей, новых регистраций.</li>
        </ul>
    </div>

    {{-- Пример ключевых метрик --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Общее количество заказов</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalOrders ?? '0' }}</p>
            <p class="text-sm text-gray-500 mt-1">За последний месяц</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Выручка</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($revenue ?? 0, 0, ',', ' ') }} руб.</p>
            <p class="text-sm text-gray-500 mt-1">За последний месяц</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800">Активные путешествия</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $activeVoyages ?? '0' }}</p>
            <p class="text-sm text-gray-500 mt-1">Текущие</p>
        </div>
    </div>

    {{-- Пример графика --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">График продаж</h3>
        {{-- Здесь можно добавить canvas для Chart.js или другой библиотеки --}}
        <div id="sales-chart" class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">График будет здесь (используйте Chart.js или аналог)</p>
        </div>
    </div>

    {{-- Пример недавней активности --}}
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Недавние заказы</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentOrders ?? [] as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->amount, 0, ',', ' ') }} руб.</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">Просмотр</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Нет недавних заказов</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Пример инициализации графика (добавьте реальные данные)
        const ctx = document.getElementById('sales-chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн'],
                datasets: [{
                    label: 'Продажи',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endpush
@endsection