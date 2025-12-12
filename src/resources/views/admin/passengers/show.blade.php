@extends('admin.admin')
@section('title', "Пассажир #{$passenger->id}")

@section('content')
    <div class="container mx-auto px-4 py-6">

        <nav class="mb-6">
            <ol class="list-reset flex text-gray-600">
                <li>
                    <a href="{{ route('admin.passengers.index') }}" class="text-blue-600 hover:text-blue-700">
                        Пассажиры
                    </a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">#{{ $passenger->id }}</li>
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
            <h1 class="text-3xl font-bold text-gray-800">Пассажир #{{ $passenger->id }}</h1>
            <div class="flex gap-3">
                <a href="{{ route('admin.passengers.edit', $passenger) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Редактировать
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Персональные данные</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $passenger->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Имя</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $passenger->first_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Фамилия</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $passenger->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Дата рождения</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->birth_date->format('d.m.Y') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Возраст</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">
                                {{ $passenger->age }} лет
                                @if($passenger->discount_percent > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        <i class="fas fa-child mr-1"></i>
                                        Детская скидка {{ $passenger->discount_percent }}%
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Серия паспорта</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->passport_series }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Номер паспорта</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->passport_number }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Гражданство</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->citizenship }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Создан</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->created_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Обновлён</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $passenger->updated_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($passenger->orderItem && $passenger->orderItem->order)
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Информация о заказе</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Номер заказа</label>
                                <p class="mt-1">
                                    <a href="{{ route('admin.orders.show', $passenger->orderItem->order) }}"
                                       class="text-blue-600 hover:text-blue-800 font-semibold text-lg">
                                        #{{ $passenger->orderItem->order->id }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Пользователь</label>
                                <p class="mt-1">
                                    @if($passenger->orderItem->order->user)
                                        <a href="{{ route('admin.users.show', $passenger->orderItem->order->user) }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $passenger->orderItem->order->user->name }}
                                        </a>
                                        <br>
                                        <span class="text-sm text-gray-500">{{ $passenger->orderItem->order->user->email }}</span>
                                    @else
                                        <span class="text-gray-400">Нет пользователя</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Статус заказа</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($passenger->orderItem->order->status)
                                            @case('Новый') bg-blue-100 text-blue-800 @break
                                            @case('Обработан') bg-yellow-100 text-yellow-800 @break
                                            @case('Оплачен') bg-green-100 text-green-800 @break
                                            @case('Отправлен') bg-purple-100 text-purple-800 @break
                                            @case('Отменён') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ $passenger->orderItem->order->status }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Дата заказа</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $passenger->orderItem->order->created_at->format('d.m.Y в H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($passenger->orderItem->ticket)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Билет</label>
                                    <p class="mt-1">
                                        <a href="{{ route('admin.tickets.show', $passenger->orderItem->ticket) }}"
                                           class="text-blue-600 hover:text-blue-800 font-semibold">
                                            #{{ $passenger->orderItem->ticket->id }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Тип каюты</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $passenger->orderItem->ticket->cabinType->name ?? 'Не указан' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Место</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        № {{ $passenger->orderItem->ticket->number }}
                                    </p>
                                </div>
                                @if($passenger->orderItem->ticket->voyage)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Рейс</label>
                                        <p class="mt-1">
                                            <a href="{{ route('admin.voyages.show', $passenger->orderItem->ticket->voyage) }}"
                                               class="text-blue-600 hover:text-blue-800">
                                                {{ $passenger->orderItem->ticket->voyage->name }}
                                            </a>
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                {{ $passenger->orderItem->ticket->voyage->departurePlace->name ?? '' }}
                                                →
                                                {{ $passenger->orderItem->ticket->voyage->arrivalPlace->name ?? '' }}
                                            </span>
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                {{ $passenger->orderItem->ticket->voyage->departure_date->format('d.m.Y H:i') }}
                                            </span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Информация о заказе</h2>
                </div>
                <div class="p-6 text-center text-gray-500">
                    Нет связанного заказа
                </div>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.passengers.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку пассажиров
            </a>
        </div>
    </div>
@endsection
