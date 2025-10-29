@extends('admin.admin')
@section('title', 'Элементы заказа')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Элементы заказа</h1>
            <a href="{{ route('admin.order-items.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Заказ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Тип</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Детали</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Цена (итого)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orderItems as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $item->order) }}" class="text-blue-600 hover:underline">
                                #{{ $item->order->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $item->type === 'ticket' ? 'Билет' : 'Развлечение' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($item->type === 'ticket')
                                {{ $item->ticket->number }}
                                <br><small class="text-gray-500">{{ $item->ticket->voyage->name ?? '—' }}</small>
                            @else
                                {{ $item->entertainment->name }}
                                @if($item->quantity > 1) <span class="text-gray-500">×{{ $item->quantity }}</span> @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.order-items.edit', $item) }}"
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Редактировать</a>

                            <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Удалить #{{ $item->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Нет элементов. <a href="{{ route('admin.order-items.create') }}" class="text-blue-600">Добавить?</a>
                        </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orderItems->links() }}</div>
    </div>
@endsection
