@extends('admin.admin')

@section('title', 'Добавить элемент заказа')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Добавить элемент заказа</h1>
            <p class="text-gray-600 mt-2">Выберите заказ и билет для добавления</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.order-items.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Заказ <span class="text-red-500">*</span></label>
                    <select name="order_id" id="order_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите заказ</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                #{{ $order->id }} ({{ $order->user->email ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-2">Билет <span class="text-red-500">*</span></label>
                    <select name="ticket_id" id="ticket_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ticket_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите билет</option>
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                {{ $ticket->number }} ({{ $ticket->voyage->name ?? 'N/A' }}, {{ number_format($ticket->price, 2, ',', ' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Сохранить</button>
                    <a href="{{ route('admin.order-items.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
