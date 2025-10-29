@extends('admin.admin')
@section('title', 'Добавить платёж')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Добавить платёж</h1>
            <p class="text-gray-600 mt-2">Введите данные нового платежа</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.payments.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Заказ <span class="text-red-500">*</span></label>
                    <select name="order_id" id="order_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order_id') border-red-500 @enderror">
                        <option value="">Выберите заказ</option>
                        @foreach($orders as $id)
                            <option value="{{ $id }}" {{ old('order_id') == $id ? 'selected' : '' }}>№{{ $id }}</option>
                        @endforeach
                    </select>
                    @error('order_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Сумма (₽) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
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
                            <option value="{{ $prov }}" {{ old('provider') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                    @error('provider')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">ID транзакции <span class="text-red-500">*</span></label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('transaction_id') border-red-500 @enderror"
                           placeholder="TXN123456789">
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
                            <option value="{{ $st }}" {{ old('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
