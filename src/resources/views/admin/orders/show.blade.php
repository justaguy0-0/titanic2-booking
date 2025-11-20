@extends('admin.admin')
@section('title', 'Заказ #{{ $order->id }}')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Заказ #{{ $order->id }}</h1>
            <p class="text-gray-600 mt-2">Детали заказа и список товаров</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-5xl">
            <!-- Основная информация -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-5 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600"><strong>Пользователь:</strong></p>
                    <p class="text-base font-medium">
                        {{ $order->user?->email ?? '<span class="text-red-600">Пользователь удалён</span>' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600"><strong>Статус заказа:</strong></p>
                    <p class="text-base">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @switch($order->status)
                                @case('Новый')      bg-blue-100 text-blue-800
                                    @break
                                @case('Обработан')  bg-yellow-100 text-yellow-800
                                    @break
                                @case('Оплачен')    bg-green-100 text-green-800
                                    @break
                                @case('Отправлен')  bg-purple-100 text-purple-800
                                    @break
                                @case('Отменён')    bg-red-100 text-red-800
                                    @break
                                @default            bg-gray-100 text-gray-800
                            @endswitch">
                            {{ $order->status }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600"><strong>Сумма:</strong></p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ number_format($order->total_price, 0, '', ' ') }} ₽
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600"><strong>Создан:</strong></p>
                    <p class="text-base">{{ $order->created_at->format('d.m.Y в H:i') }}</p>
                </div>
            </div>

            <!-- Элементы заказа -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Состав заказа</h3>

                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Наименование</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Детали</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Кол-во</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($order->orderItems as $item)
                            @php
                                $isTicket = $item->item_type === 'ticket' || $item->ticket_id !== null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $isTicket ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                            {{ $isTicket ? 'Билет' : 'Развлечение' }}
                                        </span>
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    @if($isTicket)
                                        @if($item->ticket)
                                            Каюта №{{ $item->ticket->number }}
                                        @else
                                            <span class="text-red-600 italic">Билет удалён (ID: {{ $item->ticket_id }})</span>
                                        @endif
                                    @else
                                        @if($item->entertainment)
                                            {{ $item->entertainment->name }}
                                        @else
                                            <span class="text-red-600 italic">Развлечение удалено (ID: {{ $item->entertainment_id }})</span>
                                        @endif
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($isTicket && $item->ticket)
                                        {{ $item->ticket->voyage?->name ?? 'Рейс удалён' }}
                                        @if($item->ticket->cabinType?->name)
                                            <span class="text-gray-500">— {{ $item->ticket->cabinType->name }}</span>
                                        @endif
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-center text-gray-900">
                                    {{ $item->quantity }}
                                </td>

                                <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">
                                    {{ number_format($item->price * $item->quantity, 0, '', ' ') }} ₽
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    В заказе нет элементов
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex gap-4">
                <a href="{{ route('admin.orders.edit', $order) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Редактировать заказ
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-lg transition">
                    ← Назад к списку
                </a>
            </div>
        </div>
    </div>
@endsection
