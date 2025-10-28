@extends('admin.admin')

@section('title', 'Редактировать заказ')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать заказ #{{ $order->id }}</h1>
            <p class="text-gray-600 mt-2">Измените данные, билеты и сохраните изменения</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

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

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Текущие билеты</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @forelse($order->orderItems as $item)
                            <div class="flex justify-between items-center mb-2">
                                <span>{{ $item->ticket->number }} ({{ $item->ticket->voyage->name ?? 'N/A' }}, {{ number_format($item->ticket->price, 2, ',', ' ') }} ₽)</span>
                                <form action="{{ route('admin.order-items.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Удалить билет {{ $item->ticket->number }} из заказа?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Удалить</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Билеты не выбраны.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mb-6">
                    <label for="tickets" class="block text-sm font-medium text-gray-700 mb-2">Добавить билеты</label>
                    <select name="tickets[]" id="tickets" multiple
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
                    <p class="text-gray-500 text-xs mt-1">Выберите билеты для добавления (удерживайте Ctrl/Cmd).</p>
                </div>

                <div class="mb-6">
                    <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">Сумма</label>
                    <input type="number" step="0.01" name="total_price" id="total_price" value="{{ old('total_price', $order->total_price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                    <p class="text-gray-500 text-xs mt-1">Рассчитывается автоматически на основе выбранных билетов.</p>
                </div>

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

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <p class="text-sm text-gray-600 mb-3">Удаление заказа нельзя отменить.</p>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                      onsubmit="return confirm('Вы уверены? Это удалит все связанные билеты из заказа.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">Удалить заказ</button>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            function updateTotalPrice() {
                const totalPriceInput = document.getElementById('total_price');
                let total = {{ $order->total_price }};
                document.querySelectorAll('#tickets option').forEach(option => {
                    if (option.selected) {
                        total += parseFloat(option.dataset.price || 0);
                    }
                });
                totalPriceInput.value = total.toFixed(2);
            }

            document.getElementById('tickets').addEventListener('change', updateTotalPrice);

            // Инициализация суммы при загрузке страницы
            updateTotalPrice();
        </script>
    @endsection
@endsection
