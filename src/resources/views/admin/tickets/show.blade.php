@extends('admin.admin')

@section('title', "Билет: {$ticket->number}")

@section('content')
    <div class="container mx-auto px-4 py-6">
        <nav class="mb-6">
            <ol class="list-reset flex text-gray-600">
                <li>
                    <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 hover:text-blue-700">
                        Билеты
                    </a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ $ticket->number }}</li>
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
            <h1 class="text-3xl font-bold text-gray-800">Билет: {{ $ticket->number }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.edit', $ticket) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Редактировать
                </a>
                @if($ticket->orderItems->isEmpty())
                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST"
                          onsubmit="return confirm('Удалить билет «{{ addslashes($ticket->number) }}»?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                            Удалить
                        </button>
                    </form>
                @endif
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
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $ticket->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Номер билета</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $ticket->number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Статус</label>
                            <p class="mt-1">
                                @php
                                    $statusColors = [
                                        'Доступно' => 'bg-green-100 text-green-800',
                                        'Продано' => 'bg-red-100 text-red-800',
                                        'Забронировано' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $color = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                                    {{ $ticket->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Цена</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($ticket->price, 0, '', ' ') }} ₽
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Рейс</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.voyages.show', $ticket->voyages_id) }}"
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $ticket->voyage->name ?? '—' }}
                                </a>
                            </p>
                            @if($ticket->voyage)
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $ticket->voyage->departurePlace->name ?? '—' }} →
                                    {{ $ticket->voyage->arrivalPlace->name ?? '—' }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $ticket->voyage->departure_date?->format('d.m.Y H:i') ?? '—' }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Тип каюты</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $ticket->cabinType->name ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Дата создания</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $ticket->created_at?->format('d.m.Y H:i') ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Дата обновления</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $ticket->updated_at?->format('d.m.Y H:i') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($ticket->orderItems->isNotEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Информация о заказе</h2>
                </div>
                <div class="p-6">
                    @foreach($ticket->orderItems as $orderItem)
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Номер заказа</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('admin.orders.show', $orderItem->order_id) }}"
                                           class="text-blue-600 hover:text-blue-900 font-medium">
                                            #{{ $orderItem->order_id }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Статус заказа</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $orderItem->order->status ?? '—' }}
                                    </p>
                                </div>
                            </div>
                            @if($orderItem->order)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Клиент</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $orderItem->order->user->name ?? '—' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Дата заказа</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $orderItem->order->created_at?->format('d.m.Y H:i') ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Информация о заказе</h2>
                </div>
                <div class="p-6 text-center">
                    <p class="text-gray-500">Билет не привязан к заказу</p>
                </div>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.tickets.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку билетов
            </a>
        </div>
    </div>
@endsection
