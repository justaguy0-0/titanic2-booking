@extends('admin.admin')

@section('title', 'Редактировать элемент заказа')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать элемент заказа #{{ $orderItem->id }}</h1>
            <p class="text-gray-600 mt-2">Измените заказ или билет</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.order-items.update', $orderItem) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Заказ <span class="text-red-500">*</span></label>
                    <select name="order_id" id="order_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите заказ</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" {{ old('order_id', $orderItem->order_id) == $order->id ? 'selected' : '' }}>
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
                            <option value="{{ $ticket->id }}" {{ old('ticket_id', $orderItem->ticket_id) == $ticket->id ? 'selected' : '' }}>
                                {{ $ticket->number }} ({{ $ticket->voyage->name ?? 'N/A' }}, {{ number_format($ticket->price, 2, ',', ' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600"><strong>ID:</strong> {{ $orderItem->id }}</p>
                    <p class="text-sm text-gray-600"><strong>Создано:</strong> {{ $orderItem->created_at->format('d.m.Y H:i') }}</p>
                    <p class="text-sm text-gray-600"><strong>Обновлено:</strong> {{ $orderItem->updated_at->format('d.m.Y H:i') }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Сохранить изменения</button>
                    <a href="{{ route('admin.order-items.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">Отмена</a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <p class="text-sm text-gray-600 mb-3">Удаление элемента заказа нельзя отменить.</p>
                <form action="{{ route('admin.order-items.destroy', $orderItem) }}" method="POST"
                      onsubmit="return confirm('Вы уверены, что хотите удалить элемент заказа #{{ $orderItem->id }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">Удалить элемент</button>
                </form>
            </div>
        </div>
    </div>
@endsection
