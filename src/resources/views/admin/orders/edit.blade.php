@extends('admin.admin')
@section('title', 'Редактировать заказ #{{ $order->id }}')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать заказ #{{ $order->id }}</h1>
            <p class="text-gray-600 mt-2">Измените пользователя, билеты, развлечения и статус</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Пользователь -->
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Пользователь <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>{{ $user->email }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Текущие элементы заказа -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Текущие элементы</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @forelse($order->orderItems as $item)
                            <div class="flex justify-between items-center mb-2">
                                <span>
                                    @if($item->type === 'ticket')
                                        Билет {{ $item->ticket->number }} ({{ $item->ticket->voyage->name ?? 'N/A' }}, {{ number_format($item->price, 2, ',', ' ') }} ₽)
                                    @else
                                        {{ $item->entertainment->name }} ×{{ $item->quantity }} ({{ number_format($item->price * $item->quantity, 2, ',', ' ') }} ₽)
                                    @endif
                                </span>
                                <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Удалить этот элемент из заказа?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Удалить</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Элементы не выбраны.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Добавить билеты -->
                <div class="mb-6">
                    <label for="tickets" class="block text-sm font-medium text-gray-700 mb-2">Добавить билеты</label>
                    <select name="tickets[]" id="tickets" multiple size="5"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tickets') border-red-500 @enderror">
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}" {{ in_array($ticket->id, old('tickets', [])) ? 'selected' : '' }}>
                                {{ $ticket->number }} ({{ $ticket->voyage->name ?? 'N/A' }}, {{ number_format($ticket->price, 2, ',', ' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('tickets')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Выберите билеты (Ctrl/Cmd для множественного выбора).</p>
                </div>

                <!-- Добавить развлечения -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Добавить развлечения</label>
                    <div id="entertainments-container">
                        @foreach($entertainments as $ent)
                            <div class="flex items-center gap-3 mb-2 entertainment-item">
                                <input type="checkbox" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}"
                                       class="ent-checkbox" {{ old("entertainments.{$loop->index}.id") == $ent->id ? 'checked' : '' }}>
                                <label class="flex-1">{{ $ent->name }} — {{ number_format($ent->price, 2, ',', ' ') }} ₽</label>
                                <input type="number" name="entertainments[{{ $loop->index }}][quantity]" min="1"
                                       value="{{ old("entertainments.{$loop->index}.quantity", 1) }}"
                                       class="w-20 px-3 py-2 border rounded-lg ent-quantity" disabled>
                            </div>
                        @endforeach
                    </div>
                    @error('entertainments')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Сумма -->
                <div class="mb-6">
                    <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">Сумма</label>
                    <input type="number" step="0.01" name="total_price" id="total_price" value="{{ old('total_price', $order->total_price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                    <p class="text-gray-500 text-xs mt-1">Рассчитывается автоматически.</p>
                </div>

                <!-- Статус -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="Новый" {{ old('status', $order->status) == 'Новый' ? 'selected' : '' }}>Новый</option>
                        <option value="Обработан" {{ old('status', $order->status) == 'Обработан' ? 'selected' : '' }}>Обработан</option>
                        <option value="Оплачен" {{ old('status', $order->status) == 'Оплачен' ? 'selected' : '' }}>Оплачен</option>
                        <option value="Отправлен" {{ old('status', $order->status) == 'Отправлен' ? 'selected' : '' }}>Отправлен</option>
                        <option value="Отменён" {{ old('status', $order->status) == 'Отменён' ? 'selected' : '' }}>Отменён</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Информация -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600"><strong>ID:</strong> {{ $order->id }}</p>
                    <p class="text-sm text-gray-600"><strong>Создано:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p class="text-sm text-gray-600"><strong>Обновлено:</strong> {{ $order->updated_at->format('d.m.Y H:i') }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Сохранить изменения</button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">Отмена</a>
                </div>
            </form>

            <!-- Опасная зона -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <p class="text-sm text-gray-600 mb-3">Удаление заказа нельзя отменить.</p>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                      onsubmit="return confirm('Вы уверены? Это удалит все связанные элементы заказа.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">Удалить заказ</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const ticketsSelect = document.getElementById('tickets');
            const totalPriceInput = document.getElementById('total_price');

            function updateTotalPrice() {
                let total = {{ $order->total_price }};

                // Билеты
                Array.from(ticketsSelect.options).forEach(option => {
                    if (option.selected) {
                        total += parseFloat(option.dataset.price || 0);
                    }
                });

                // Развлечения
                document.querySelectorAll('.entertainment-item').forEach(item => {
                    const checkbox = item.querySelector('.ent-checkbox');
                    const quantityInput = item.querySelector('.ent-quantity');
                    if (checkbox.checked && quantityInput.value) {
                        const price = parseFloat(checkbox.closest('.entertainment-item').querySelector('label').textContent.match(/[\d.,]+/)[0].replace(',', '.'));
                        total += price * parseInt(quantityInput.value);
                    }
                });

                totalPriceInput.value = total.toFixed(2);
            }

            ticketsSelect.addEventListener('change', updateTotalPrice);

            document.querySelectorAll('.ent-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const quantityInput = this.closest('.entertainment-item').querySelector('.ent-quantity');
                    quantityInput.disabled = !this.checked;
                    if (!this.checked) quantityInput.value = 1;
                    updateTotalPrice();
                });
            });

            document.querySelectorAll('.ent-quantity').forEach(input => {
                input.addEventListener('input', updateTotalPrice);
            });

            updateTotalPrice();
        </script>
    @endpush
@endsection
