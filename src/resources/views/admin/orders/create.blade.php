@extends('admin.admin')

@section('title', 'Добавить заказ')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Добавить заказ</h1>
            <p class="text-gray-600 mt-2">Выберите пользователя, билеты и статус для нового заказа</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Пользователь <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->email }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tickets" class="block text-sm font-medium text-gray-700 mb-2">Билеты <span class="text-red-500">*</span></label>
                    <select name="tickets[]" id="tickets" multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tickets') border-red-500 @enderror"
                            required>
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" {{ in_array($ticket->id, old('tickets', [])) ? 'selected' : '' }}>
                                {{ $ticket->number }} ({{ $ticket->voyage->name ?? 'N/A' }}, {{ number_format($ticket->price, 2, ',', ' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('tickets')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Выберите один или несколько билетов (удерживайте Ctrl/Cmd для множественного выбора).</p>
                </div>

                <div class="mb-6">
                    <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">Сумма</label>
                    <input type="number" step="0.01" name="total_price" id="total_price" value="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                    <p class="text-gray-500 text-xs mt-1">Рассчитывается автоматически на основе выбранных билетов.</p>
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="Новый" {{ old('status') == 'Новый' ? 'selected' : '' }}>Новый</option>
                        <option value="Обработан" {{ old('status') == 'Обработан' ? 'selected' : '' }}>Обработан</option>
                        <option value="Оплачен" {{ old('status') == 'Оплачен' ? 'selected' : '' }}>Оплачен</option>
                        <option value="Отправлен" {{ old('status') == 'Отправлен' ? 'selected' : '' }}>Отправлен</option>
                        <option value="Отменён" {{ old('status') == 'Отменён' ? 'selected' : '' }}>Отменён</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Сохранить</button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('tickets').addEventListener('change', function () {
                const totalPriceInput = document.getElementById('total_price');
                let total = 0;
                Array.from(this.options).forEach(option => {
                    if (option.selected) {
                        total += parseFloat(option.dataset.price || 0);
                    }
                });
                totalPriceInput.value = total.toFixed(2);
            });
        </script>
    @endsection
@endsection
