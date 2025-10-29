@extends('admin.admin')
@section('title', 'Редактировать элемент заказа #{{ $orderItem->id }}')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Редактировать элемент заказа #{{ $orderItem->id }}
        </h1>
        <p class="text-gray-600 mb-6">Можно изменить заказ, тип и детали.</p>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            <form action="{{ route('admin.order-items.update', $orderItem) }}" method="POST">
                @csrf @method('PUT')

                <!-- Заказ -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Заказ <span class="text-red-500">*</span></label>
                    <select name="order_id" required
                            class="w-full px-4 py-2 border rounded-lg @error('order_id') border-red-500 @enderror">
                        <option value="">— выберите —</option>
                        @foreach($orders as $o)
                            <option value="{{ $o->id }}" {{ old('order_id',$orderItem->order_id)==$o->id?'selected':'' }}>
                                #{{ $o->id }} ({{ $o->user->email ?? '—' }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Тип (нельзя менять, если уже есть билет/развлечение) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Текущий тип</label>
                    <p class="font-semibold">
                        {{ $orderItem->type === 'ticket' ? 'Билет' : 'Развлечение' }}
                    </p>
                </div>

                @if($orderItem->type === 'ticket')
                    <!-- Билет -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Билет <span class="text-red-500">*</span></label>
                        <select name="ticket_id" required
                                class="w-full px-4 py-2 border rounded-lg @error('ticket_id') border-red-500 @enderror">
                            <option value="">— выберите —</option>
                            @foreach($tickets as $t)
                                <option value="{{ $t->id }}" {{ old('ticket_id',$orderItem->ticket_id)==$t->id?'selected':'' }}>
                                    {{ $t->number }} ({{ $t->voyage->name ?? '—' }}, {{ number_format($t->price,2,',',' ') }} ₽)
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                @else
                    <!-- Развлечение -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Развлечение <span class="text-red-500">*</span></label>
                        <select name="entertainment_id" required
                                class="w-full px-4 py-2 border rounded-lg @error('entertainment_id') border-red-500 @enderror">
                            <option value="">— выберите —</option>
                            @foreach($entertainments as $e)
                                <option value="{{ $e->id }}" {{ old('entertainment_id',$orderItem->entertainment_id)==$e->id?'selected':'' }}>
                                    {{ $e->name }} – {{ number_format($e->price,2,',',' ') }} ₽
                                </option>
                            @endforeach
                        </select>
                        @error('entertainment_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Количество</label>
                        <input type="number" name="quantity" min="1"
                               value="{{ old('quantity',$orderItem->quantity) }}"
                               class="w-24 px-3 py-2 border rounded-lg">
                    </div>
                @endif

                <div class="flex gap-3 mt-8">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.order-items.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>

            <!-- Удаление -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <form action="{{ route('admin.order-items.destroy', $orderItem) }}" method="POST"
                      onsubmit="return confirm('Удалить элемент #{{ $orderItem->id }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Удалить элемент
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
