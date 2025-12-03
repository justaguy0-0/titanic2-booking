@extends('admin.admin')
@section('title', 'Детали заказа')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Детали заказа</h1>
            <a href="{{ route('admin.order-items.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить элемент
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
                        <a href="{{ $sortUrl('order_id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Заказ <span class="text-gray-400">{{ $sortIcon('order_id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('type') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Тип <span class="text-gray-400">{{ $sortIcon('type') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('item_name') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Детали <span class="text-gray-400">{{ $sortIcon('item_name') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('total_price') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Итого <span class="text-gray-400">{{ $sortIcon('total_price') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orderItems as $item)
                    @php
                        $isTicket = $item->type === 'ticket';
                        $badgeClass = $isTicket
                            ? 'bg-indigo-100 text-indigo-800'
                            : 'bg-teal-100 text-teal-800';
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $item->order) }}"
                               class="text-blue-600 hover:underline font-medium">
                                #{{ $item->order->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $isTicket ? 'Билет' : 'Развлечение' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($isTicket)
                                <div class="font-medium">{{ $item->ticket?->number ?? '—' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->ticket?->voyage?->name ?? 'Рейс удалён' }}
                                </div>
                            @else
                                <div>{{ $item->entertainment?->name ?? '—' }}</div>
                                @if($item->quantity > 1)
                                    <span class="text-xs text-gray-500">×{{ $item->quantity }}</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($item->price * $item->quantity, 0, '', ' ') }} ₽
                        </td>

                        {{-- Только иконки в действиях --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.order-items.edit', $item) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Удалить элемент #{{ $item->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Нет элементов заказа.
                            <a href="{{ route('admin.order-items.create') }}" class="text-blue-600 hover:underline">
                                Добавить первый?
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orderItems->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
