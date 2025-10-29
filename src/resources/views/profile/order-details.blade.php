<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('profile.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Назад к заказам
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Order Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Информация о заказе</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Номер заказа</p>
                            <p class="text-base font-semibold text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Дата оформления</p>
                            <p class="text-base font-semibold text-gray-900">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Статус</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->status === 'Новый') bg-blue-100 text-blue-800
                                @elseif($order->status === 'Обработан') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'Оплачен') bg-green-100 text-green-800
                                @elseif($order->status === 'Отправлен') bg-purple-100 text-purple-800
                                @elseif($order->status === 'Отменён') bg-red-100 text-red-800
                                @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Общая сумма</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($order->total_price, 2) }} ₽</p>
                        </div>
                    </div>

                    @if(in_array($order->status, ['Новый', 'Обработан']))
                        <div class="mt-6">
                            <form action="{{ route('profile.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите отменить заказ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Отменить заказ
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            @if($order->orderItems && $order->orderItems->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Состав заказа</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Позиция
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Тип
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Цена
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Количество
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Сумма
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm">
                                                    @if($item->item_type === 'ticket' && $item->ticket)
                                                        <div>
                                                            <p class="font-semibold text-gray-900">{{ $item->ticket->type }}</p>
                                                            <p class="text-xs text-gray-500">Билет № {{ $item->ticket->number }}</p>
                                                            @if($item->ticket->voyage)
                                                                <p class="text-xs text-gray-500 mt-1">
                                                                    <span class="font-medium">{{ $item->ticket->voyage->name }}</span>
                                                                </p>
                                                                <p class="text-xs text-gray-400">
                                                                    {{ $item->ticket->voyage->placeDeparture->name }} → {{ $item->ticket->voyage->icebergArrival->name }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @elseif($item->item_type === 'entertainment' && $item->entertainment)
                                                        <p class="font-semibold text-gray-900">{{ $item->entertainment->name }}</p>
                                                        <p class="text-xs text-gray-500">Развлечение</p>
                                                    @else
                                                        <span class="text-gray-400">Позиция удалена</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($item->item_type === 'ticket') bg-blue-100 text-blue-800
                                                    @else bg-purple-100 text-purple-800
                                                    @endif">
                                                    @if($item->item_type === 'ticket') Билет
                                                    @else Развлечение
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($item->price, 2) }} ₽
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->quantity }} шт.
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ number_format($item->price * $item->quantity, 2) }} ₽
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                            Итого:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900">
                                            {{ number_format($order->total_price, 2) }} ₽
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>