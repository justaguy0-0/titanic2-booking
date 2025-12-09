@extends('admin.admin')

@section('title', 'Добавить заказ')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Добавить заказ</h1>
            <p class="text-gray-600 mt-2">Выберите пользователя, билеты, развлечения и статус</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-4xl">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Пользователь <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->email }} ({{ $user->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Билеты <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="ticket-search"
                           placeholder="Поиск по номеру, рейсу, каюте..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-3"
                           autocomplete="off">

                    <div id="selected-tickets" class="flex flex-wrap gap-2 mb-4"></div>

                    <div class="border border-gray-300 rounded-lg max-h-96 overflow-y-auto bg-white">
                        @foreach(\App\Models\Ticket::where('status', 'Доступно')->orderBy('number')->get() as $ticket)
                            <label class="ticket-item flex items-center px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                <input type="checkbox"
                                       name="tickets[]"
                                       value="{{ $ticket->id }}"
                                       data-price="{{ $ticket->price }}"
                                       data-text="{{ $ticket->number }} — {{ $ticket->voyage->name ?? '—' }} — {{ number_format($ticket->price, 0, '', ' ') }} ₽"
                                       class="ticket-checkbox rounded text-blue-600 focus:ring-blue-500"
                                    {{ old('tickets') && in_array($ticket->id, old('tickets', [])) ? 'checked' : '' }}>

                                <span class="ml-3 flex-1 text-sm">
                                    <span class="font-medium">{{ $ticket->number }}</span>
                                    — {{ $ticket->voyage->name ?? '—' }}
                                    <span class="text-green-600 font-semibold">{{ number_format($ticket->price, 0, '', ' ') }} ₽</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-3 text-sm text-gray-600">
                        Выбрано: <span id="selected-count" class="font-bold text-blue-600">0</span> билетов |
                        Сумма: <span id="selected-sum" class="font-bold text-green-700">0</span> ₽
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Развлечения</label>
                    <div id="entertainments-container">
                        @foreach($entertainments as $ent)
                            <div class="flex items-center gap-3 mb-2 entertainment-item">
                                <input type="checkbox"
                                       name="entertainments[{{ $loop->index }}][id]"
                                       value="{{ $ent->id }}"
                                       class="ent-checkbox"
                                    {{ in_array($ent->id, old('entertainments.*.id', [])) ? 'checked' : '' }}>

                                <label class="flex-1">{{ $ent->name }} — {{ number_format($ent->price, 0, '', ' ') }} ₽</label>

                                <input type="number"
                                       name="entertainments[{{ $loop->index}}][quantity]"
                                       min="1"
                                       value="1"
                                       class="w-20 px-3 py-2 border rounded-lg ent-quantity"
                                    {{ in_array($ent->id, old('entertainments.*.id', [])) ? '' : 'disabled' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Итого к оплате</label>
                    <div id="total_price_display" class="text-3xl font-bold text-green-600">0 ₽</div>
                    <input type="hidden" name="total_price" id="total_price" value="0">
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Статус <span class="text-red-500">*</span>
                    </label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <option value="Новый" {{ old('status', 'Новый') == 'Новый' ? 'selected' : '' }}>Новый</option>
                        <option value="Обработан" {{ old('status') == 'Обработан' ? 'selected' : '' }}>Обработан</option>
                        <option value="Оплачен" {{ old('status') == 'Оплачен' ? 'selected' : '' }}>Оплачен</option>
                        <option value="Отправлен" {{ old('status') == 'Отправлен' ? 'selected' : '' }}>Отправлен</option>
                        <option value="Отменён" {{ old('status') == 'Отменён' ? 'selected' : '' }}>Отменён</option>
                    </select>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                        Создать заказ
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-8 rounded-lg">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    @vite('resources/js/admin/order-create.js')
@endsection
