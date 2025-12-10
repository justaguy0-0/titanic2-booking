@extends('admin.admin')
@section('title', 'Редактировать заказ')
@section('content')
    @section('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection

    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать заказ</h1>
            <p class="text-gray-600 mt-2">Измените пользователя, билеты, развлечения и статус</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="editOrderForm">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Пользователь <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror" required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->email }} ({{ $user->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Текущие билеты в заказе</label>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2" id="current-tickets-container">
                        @php
                            $currentTickets = $order->orderItems->where('item_type', 'ticket');
                            $currentTicketIds = $currentTickets->pluck('ticket_id')->toArray();
                        @endphp
                        @forelse($currentTickets as $item)
                            <div class="current-ticket-item flex justify-between items-center py-2 px-3 bg-white rounded border"
                                 data-price="{{ $item->price }}"
                                 data-item-id="{{ $item->id }}">
                                <span class="text-sm">
                                    Билет {{ $item->ticket->number }}
                                    ({{ $item->ticket->voyage->name ?? '—' }})
                                    — <strong>{{ number_format($item->price, 0, '', ' ') }} ₽</strong>
                                </span>
                                <input type="hidden" name="existing_tickets[]" value="{{ $item->ticket_id }}">
                                <button type="button"
                                        class="delete-ticket-btn text-red-600 hover:text-red-900 text-sm"
                                        data-item-id="{{ $item->id }}"
                                        data-delete-url="{{ route('admin.order-items.destroy', $item) }}">
                                    Удалить
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Билетов пока нет</p>
                        @endforelse
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Добавить билеты
                    </label>
                    <input type="text"
                           id="ticket-search"
                           placeholder="Поиск по номеру, рейсу, каюте..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-3"
                           autocomplete="off">

                    <div id="selected-tickets" class="flex flex-wrap gap-2 mb-4"></div>

                    <div class="border border-gray-300 rounded-lg max-h-96 overflow-y-auto bg-white">
                        @php
                            $availableTickets = \App\Models\Ticket::where('status', 'Доступно')
                                ->whereNotIn('id', $currentTicketIds)
                                ->orderBy('number')
                                ->get();
                        @endphp
                        @foreach($availableTickets as $ticket)
                            <label class="ticket-item flex items-center px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                <input type="checkbox"
                                       name="tickets[]"
                                       value="{{ $ticket->id }}"
                                       data-price="{{ $ticket->price }}"
                                       data-text="{{ $ticket->number }} — {{ $ticket->voyage->name ?? '—' }} — {{ number_format($ticket->price, 0, '', ' ') }} ₽"
                                       class="ticket-checkbox rounded text-blue-600 focus:ring-blue-500"
                                    {{ in_array($ticket->id, old('tickets', [])) ? 'checked' : '' }}>
                                <span class="ml-3 flex-1 text-sm">
                                    <span class="font-medium">{{ $ticket->number }}</span>
                                    — {{ $ticket->voyage->name ?? '—' }}
                                    <span class="text-green-600 font-semibold">{{ number_format($ticket->price, 0, '', ' ') }} ₽</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-3 text-sm text-gray-600">
                        Выбрано: <span id="selected-count" class="font-bold text-blue-600">0</span> новых билетов
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Развлечения</label>
                    <div id="entertainments-container">
                        @php
                            $existingEntertainments = $order->orderItems
                                ->where('item_type', 'entertainment')
                                ->keyBy('entertainment_id')
                                ->toArray();
                        @endphp

                        @foreach($entertainments as $index => $ent)
                            @php
                                $existing = $existingEntertainments[$ent->id] ?? null;
                                $isChecked = old("entertainments.{$ent->id}.checked", $existing ? true : false);
                                $quantity = old("entertainments.{$ent->id}.quantity", $existing['quantity'] ?? 1);
                            @endphp
                            <div class="entertainment-item flex items-center gap-3 mb-2">
                                <input type="checkbox"
                                       name="entertainments[{{ $ent->id }}][id]"
                                       value="{{ $ent->id }}"
                                       class="ent-checkbox"
                                       data-price="{{ $ent->price }}"
                                    {{ $isChecked ? 'checked' : '' }}>

                                <label class="flex-1 cursor-pointer">
                                    {{ $ent->name }} — {{ number_format($ent->price, 0, '', ' ') }} ₽
                                </label>

                                <input type="number"
                                       name="entertainments[{{ $ent->id }}][quantity]"
                                       min="1"
                                       value="{{ $quantity }}"
                                       class="ent-quantity w-20 px-3 py-2 border rounded-lg"
                                    {{ $isChecked ? '' : 'disabled' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Итого к оплате</label>
                    <div id="total_price_display" class="text-3xl font-bold text-green-600">
                        {{ number_format($order->total_price, 0, '', ' ') }} ₽
                    </div>
                    <input type="hidden" name="total_price" id="total_price" value="{{ $order->total_price }}">
                    <input type="hidden" name="final_price" id="final_price" value="{{ $order->final_price }}">
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        @foreach(['Новый', 'Обработан', 'Оплачен', 'Отправлен', 'Отменён'] as $status)
                            <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p><strong>ID:</strong> {{ $order->id }}</p>
                    <p><strong>Создано:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Обновлено:</strong> {{ $order->updated_at->format('d.m.Y H:i') }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="save-button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">Сохранить</button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg">Отмена</a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Удалить заказ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg">Удалить заказ</button>
                </form>
            </div>
        </div>
    </div>

    @vite('resources/js/admin/order-edit.js')
@endsection
