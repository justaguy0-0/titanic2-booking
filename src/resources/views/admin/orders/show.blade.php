@extends('admin.admin')
@section('title', 'Просмотр заказа #{{ $order->id }}')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Заказ #{{ $order->id }}</h1>
            <p class="text-gray-600 mt-2">Детали заказа, билеты и развлечения</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600"><strong>Пользователь:</strong> {{ $order->user->email ?? 'Не указано' }}</p>
                <p class="text-sm text-gray-600"><strong>Сумма:</strong> {{ number_format($order->total_price, 2, ',', ' ') }} руб.</p>
                <p class="text-sm text-gray-600"><strong>Статус:</strong> {{ $order->status }}</p>
                <p class="text-sm text-gray-600"><strong>Создано:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                <p class="text-sm text-gray-600"><strong>Обновлено:</strong> {{ $order->updated_at->format('d.m.Y H:i') }}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Элементы заказа</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @forelse($order->orderItems as $item)
                        <div class="mb-2">
                            <p class="text-sm text-gray-600">
                                @if($item->type === 'ticket')
                                    <strong>Билет {{ $item->ticket->number }}</strong> ({{ $item->ticket->voyage->name ?? 'N/A' }}, {{ number_format($item->price, 2, ',', ' ') }} ₽)
                                @else
                                    <strong>{{ $item->entertainment->name }}</strong> ×{{ $item->quantity }} ({{ number_format($item->price * $item->quantity, 2, ',', ' ') }} ₽)
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Элементы не выбраны.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.orders.edit', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Редактировать</a>
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">Назад</a>
            </div>
        </div>
    </div>
@endsection
