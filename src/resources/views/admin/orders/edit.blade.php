@extends('admin.admin')
@section('title', 'Редактировать заказ')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать заказ</h1>
            <p class="text-gray-600 mt-2">Измените пользователя, билеты, развлечения и статус</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Пользователь -->
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Пользователь <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror" required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>{{ $user->email }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Текущие билеты -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Текущие билеты</label>
                    <div class="bg-gray-50 p-4 rounded-lg" id="current-tickets">
                        @php
                            $currentTickets = $order->orderItems->where('type', 'ticket');
                        @endphp
                        @forelse($currentTickets as $item)
                            <div class="flex justify-between items-center mb-2 current-ticket-item" data-price="{{ $item->price }}">
                                <span>
                                    Билет {{ $item->ticket->number }} ({{ $item->ticket->voyage->name ?? 'N/A' }}, {{ number_format($item->price, 2, ',', ' ') }} ₽)
                                </span>
                                <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Удалить?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Удалить</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Билеты не выбраны.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Добавить билеты -->
                <div class="mb-6">
                    <label for="tickets" class="block text-sm font-medium text-gray-700 mb-2">Добавить билеты</label>
                    <select name="tickets[]" id="tickets" multiple size="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tickets') border-red-500 @enderror">
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}" {{ in_array($ticket->id, old('tickets', [])) ? 'selected' : '' }}>
                                {{ $ticket->number }} ({{ $ticket->voyage->name ?? 'N/A' }}, {{ number_format($ticket->price, 2, ',', ' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('tickets') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-xs mt-1">Ctrl/Cmd для множественного выбора.</p>
                </div>

                <!-- Добавить развлечения -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Добавить развлечения</label>
                    <div id="entertainments-container">
                        @foreach($entertainments as $ent)
                            @php
                                $isChecked = !old() && isset($existingEntertainments[$ent->id]);
                                $quantity = $existingEntertainments[$ent->id] ?? 1;
                            @endphp

                            <div class="flex items-center gap-3 mb-2 entertainment-item" data-price="{{ $ent->price }}" data-ent-id="{{ $ent->id }}">
                                <input type="checkbox"
                                       class="ent-checkbox"
                                       data-ent-id="{{ $ent->id }}"
                                    {{ $isChecked ? 'checked' : '' }}>

                                <label class="flex-1">
                                    {{ $ent->name }} — {{ number_format($ent->price, 2, ',', ' ') }} ₽
                                </label>

                                <input type="number"
                                       min="1"
                                       value="{{ $quantity }}"
                                       class="w-20 px-3 py-2 border rounded-lg ent-quantity"
                                       data-ent-id="{{ $ent->id }}"
                                    {{ $isChecked ? '' : 'disabled' }}>
                            </div>
                        @endforeach

                        <!-- Контейнер для скрытых полей (будем генерировать при отправке) -->
                        <div id="entertainments-hidden"></div>
                    </div>
                    @error('entertainments') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Сумма -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Сумма</label>
                    <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', $order->total_price) }}">
                    <div id="total_price_display" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        {{ number_format($order->total_price, 2, ',', ' ') }} ₽
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Рассчитывается автоматически.</p>
                </div>

                <!-- Статус -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        @foreach(['Новый', 'Обработан', 'Оплачен', 'Отправлен', 'Отменён'] as $status)
                            <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Информация -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p><strong>ID:</strong> {{ $order->id }}</p>
                    <p><strong>Создано:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Обновлено:</strong> {{ $order->updated_at->format('d.m.Y H:i') }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">Сохранить</button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg">Отмена</a>
                </div>
            </form>

            <!-- Опасная зона -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Удалить заказ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg">Удалить заказ</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ticketsSelect = document.getElementById('tickets');
                const totalPriceInput = document.getElementById('total_price');
                const totalPriceDisplay = document.getElementById('total_price_display');
                const form = document.getElementById('user_id').closest('form');

                function updateTotalPrice() {
                    let total = 0;

                    document.querySelectorAll('.current-ticket-item').forEach(item => {
                        total += parseFloat(item.dataset.price || 0);
                    });

                    Array.from(ticketsSelect.selectedOptions).forEach(option => {
                        total += parseFloat(option.dataset.price || 0);
                    });

                    document.querySelectorAll('.entertainment-item').forEach(item => {
                        const checkbox = item.querySelector('.ent-checkbox');
                        const quantityInput = item.querySelector('.ent-quantity');
                        if (checkbox.checked && quantityInput.value) {
                            const price = parseFloat(item.dataset.price || 0);
                            const quantity = parseInt(quantityInput.value) || 1;
                            total += price * quantity;
                        }
                    });

                    totalPriceInput.value = total.toFixed(2);
                    totalPriceDisplay.textContent = new Intl.NumberFormat('ru-RU', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(total) + ' ₽';
                }

                // === ОБРАБОТКА ОТПРАВКИ ФОРМЫ ===
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Останавливаем, чтобы успеть добавить поля

                    const container = document.getElementById('entertainments-hidden');
                    container.innerHTML = '';

                    let index = 0;
                    document.querySelectorAll('.entertainment-item').forEach(item => {
                        const checkbox = item.querySelector('.ent-checkbox');
                        const quantityInput = item.querySelector('.ent-quantity');
                        if (checkbox && checkbox.checked && quantityInput && quantityInput.value) {
                            const entId = item.getAttribute('data-ent-id'); // Исправлено: getAttribute
                            const quantity = quantityInput.value;

                            const idInput = document.createElement('input');
                            idInput.type = 'hidden';
                            idInput.name = `entertainments[${index}][id]`;
                            idInput.value = entId;

                            const qtyInput = document.createElement('input');
                            qtyInput.type = 'hidden';
                            qtyInput.name = `entertainments[${index}][quantity]`;
                            qtyInput.value = quantity;

                            container.appendChild(idInput);
                            container.appendChild(qtyInput);
                            index++;
                        }
                    });

                    // Отправляем форму вручную
                    form.submit();
                });

                // === СОБЫТИЯ ===
                ticketsSelect.addEventListener('change', updateTotalPrice);

                document.querySelectorAll('.ent-checkbox').forEach(cb => {
                    cb.addEventListener('change', function () {
                        const qty = this.closest('.entertainment-item').querySelector('.ent-quantity');
                        qty.disabled = !this.checked;
                        if (!this.checked) qty.value = 1;
                        updateTotalPrice();
                    });
                });

                document.querySelectorAll('.ent-quantity').forEach(input => {
                    input.addEventListener('input', updateTotalPrice);
                });

                updateTotalPrice();
            });
        </script>
    @endpush
@endsection
