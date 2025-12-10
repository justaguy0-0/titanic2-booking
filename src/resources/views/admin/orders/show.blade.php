@extends('admin.admin')
@section('title', "Заказ #{$order->id}")

@section('content')
    <div class="container mx-auto px-4 py-6">

        <nav class="mb-6">
            <ol class="list-reset flex text-gray-600">
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700">
                        Заказы
                    </a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">#{{ $order->id }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Заказ #{{ $order->id }}</h1>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.edit', $order) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Редактировать
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Основная информация</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID заказа</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Пользователь</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($order->user)
                                    <a href="{{ route('admin.users.show', $order->user) }}"
                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $order->user->name }} ({{ $order->user->email }})
                                    </a>
                                @else
                                    <span class="text-red-600 italic">Пользователь удалён</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Статус</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @switch($order->status)
                                        @case('Новый') bg-blue-100 text-blue-800 @break
                                        @case('Обработан') bg-yellow-100 text-yellow-800 @break
                                        @case('Оплачен') bg-green-100 text-green-800 @break
                                        @case('Отправлен') bg-purple-100 text-purple-800 @break
                                        @case('Отменён') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $order->status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Сумма заказа</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">
                                {{ number_format($order->total_price, 0, '', ' ') }} ₽
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Создан</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $order->created_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Обновлён</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $order->updated_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Состав заказа</h2>
            </div>
            <div class="p-6">
                @if($order->orderItems->isNotEmpty())
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                            @php
                                $isTicket = $item->item_type === 'ticket' || $item->ticket_id !== null;
                            @endphp
                            <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Тип</label>
                                        <p class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $isTicket ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                                {{ $isTicket ? 'Билет' : 'Развлечение' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Наименование</label>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            @if($isTicket)
                                                @if($item->ticket)
                                                    Каюта №{{ $item->ticket->number }}
                                                @else
                                                    <span class="text-red-600 italic">Билет удалён</span>
                                                @endif
                                            @else
                                                @if($item->entertainment)
                                                    {{ $item->entertainment->name }}
                                                @else
                                                    <span class="text-red-600 italic">Развлечение удалено</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Детали</label>
                                        <p class="mt-1 text-sm text-gray-600">
                                            @if($isTicket && $item->ticket?->voyage)
                                                {{ $item->ticket->voyage->name }}
                                                @if($item->ticket->cabinType)
                                                    <span class="text-gray-500">— {{ $item->ticket->cabinType->name }}</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Количество</label>
                                        <p class="mt-1 text-sm font-medium">{{ $item->quantity }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Цена за единицу</label>
                                        <p class="mt-1 text-sm">{{ number_format($item->price, 0, '', ' ') }} ₽</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Итого</label>
                                        <p class="mt-1 text-sm font-bold text-gray-900">
                                            {{ number_format($item->price * $item->quantity, 0, '', ' ') }} ₽
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">В заказе нет элементов</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку заказов
            </a>
        </div>
    </div>
@endsection
