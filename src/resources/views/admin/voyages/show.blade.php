@extends('admin.admin')

@section('title', "Рейс: {$voyage->name}")

@section('content')
    <div class="container mx-auto px-4 py-6">
        <nav class="mb-6">
            <ol class="list-reset flex text-gray-600">
                <li>
                    <a href="{{ route('admin.voyages.index') }}" class="text-blue-600 hover:text-blue-700">
                        Рейсы
                    </a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">{{ $voyage->name }}</li>
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
            <h1 class="text-3xl font-bold text-gray-800">Рейс: {{ $voyage->name }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.voyages.edit', $voyage) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Редактировать
                </a>
                @if(!$voyage->departure_date?->isPast())
                    <form action="{{ route('admin.voyages.destroy', $voyage) }}" method="POST"
                          onsubmit="return confirm('Удалить рейс «{{ addslashes($voyage->name) }}»?')">
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
                            <p class="mt-1 text-sm text-gray-900">{{ $voyage->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Название</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $voyage->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Место отправления</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $voyage->departurePlace->name ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Место прибытия</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $voyage->arrivalPlace->name ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Дата и время отправления</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $voyage->departure_date ? $voyage->departure_date->format('d.m.Y H:i') : '—' }}
                                @if($voyage->departure_date?->isToday())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Сегодня
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Дата и время прибытия</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $voyage->arrival_date ? $voyage->arrival_date->format('d.m.Y H:i') : '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Время в пути</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $voyage->travel_time }} часов</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Базовая цена</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $voyage->formatted_price }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    @php
                        $now = now();
                        $status = 'upcoming';
                        $statusText = 'Предстоящий';
                        $statusColor = 'bg-blue-100 text-blue-800';

                        if ($voyage->departure_date && $voyage->departure_date->lte($now)) {
                            if ($voyage->arrival_date && $voyage->arrival_date->lte($now)) {
                                $status = 'completed';
                                $statusText = 'Завершён';
                                $statusColor = 'bg-gray-100 text-gray-800';
                            } else {
                                $status = 'in_progress';
                                $statusText = 'В пути';
                                $statusColor = 'bg-green-100 text-green-800';
                            }
                        }
                    @endphp
                    <div class="flex items-center">
                        <label class="text-sm font-medium text-gray-500 mr-4">Статус рейса:</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Билеты</h2>
                <span class="text-sm text-gray-500">
                    Всего: {{ $tickets->total() }} •
                    Доступно: {{ $voyage->available_tickets_count ?? 0 }}
                </span>
            </div>

            @if($tickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID билета
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Цена
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Место
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ticket->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $statusColors = [
                                            'Доступно' => 'bg-green-100 text-green-800',
                                            'Продано' => 'bg-red-100 text-red-800',
                                            'Забронировано' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                        $color = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ $ticket->status }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($ticket->price, 2, '.', ' ') }} ₽
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $ticket->seat_number ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Просмотр
                                    </a>
                                    <a href="#" class="text-blue-600 hover:text-blue-900">
                                        Редактировать
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-gray-500">Нет билетов для этого рейса</p>
                    <a href="#" class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                        Добавить билеты
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.voyages.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку рейсов
            </a>
        </div>
    </div>
@endsection
