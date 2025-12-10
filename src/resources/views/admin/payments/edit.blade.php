@extends('admin.admin')
@section('title', 'Редактировать платёж')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать платёж #{{ $payment->id }}</h1>
            <p class="text-gray-600 mt-2">Измените данные и сохраните</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Заказ <span class="text-red-500">*</span></label>
                    <select name="order_id" id="order_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order_id') border-red-500 @enderror">
                        <option value="">Выберите заказ</option>
                        @foreach($orders as $id)
                            <option value="{{ $id }}" {{ old('order_id', $payment->order_id) == $id ? 'selected' : '' }}>№{{ $id }}</option>
                        @endforeach
                    </select>
                    @error('order_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Сумма (₽) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror">
                    @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">Провайдер <span class="text-red-500">*</span></label>
                    <select name="provider" id="provider" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('provider') border-red-500 @enderror">
                        <option value="">Выберите провайдера</option>
                        @foreach(['Visa', 'MasterCard', 'SBP', 'Tinkoff', 'Yandex'] as $prov)
                            <option value="{{ $prov }}" {{ old('provider', $payment->provider) == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                    @error('provider')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">ID транзакции <span class="text-red-500">*</span></label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('transaction_id') border-red-500 @enderror">
                    @error('transaction_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="">Выберите статус</option>
                        @foreach(['Успешно', 'Отклонено', 'В обработке'] as $st)
                            <option value="{{ $st }}" {{ old('status', $payment->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <p class="text-sm text-gray-600 mb-3">Удаление платежа нельзя отменить.</p>
                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST"
                      onsubmit="return confirm('Вы уверены, что хотите удалить этот платёж?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Удалить платёж
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
