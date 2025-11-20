@extends('admin.admin')
@section('title', 'Заказы')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Заказы</h1>
            <a href="{{ route('admin.orders.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить заказ
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    @php
                        $currentSort = request('sort', 'id');
                        $currentDir = request('direction', 'desc');
                        $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
                        $sortUrl = fn($field) => request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => $currentSort === $field ? $nextDir : 'asc'
                        ]);
                        $sortIcon = fn($field) => $currentSort === $field
                            ? ($currentDir === 'asc' ? '↑' : '↓')
                            : '';
                    @endphp
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ID <span class="text-gray-400">{{ $sortIcon('id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('user_email') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Пользователь <span class="text-gray-400">{{ $sortIcon('user_email') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Элементы
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('total_price') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Сумма <span class="text-gray-400">{{ $sortIcon('total_price') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('status') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Статус <span class="text-gray-400">{{ $sortIcon('status') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Создано <span class="text-gray-400">{{ $sortIcon('created_at') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    @php
                        $statusColor = match($order->status) {
                            'Новый' => 'bg-blue-100 text-blue-800',
                            'Обработан' => 'bg-yellow-100 text-yellow-800',
                            'Оплачен' => 'bg-green-100 text-green-800',
                            'Отправлен' => 'bg-purple-100 text-purple-800',
                            'Отменён' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($order->user)
                                <a href="mailto:{{ $order->user->email }}" class="text-blue-600 hover:underline">
                                    {{ $order->user->email }}
                                </a>
                            @else
                                <span class="text-gray-400">Удалённый пользователь</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $order->orderItems->count() }} шт.
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($order->total_price, 0, '', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>

                        {{-- Только иконки в действиях --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-gray-600 hover:text-blue-600" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.orders.edit', $order) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Рedактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Удалить заказ #{{ $order->id }} и все его элементы?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Нет заказов.
                            <a href="{{ route('admin.orders.create') }}" class="text-blue-600 hover:underline">Добавить первый?</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
