@extends('admin.admin')

@section('title', 'Редактировать элемент заказа #' . $orderItem->id)

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать элемент заказа #{{ $orderItem->id }}</h1>
            <p class="text-gray-600 mt-2">
                Текущий элемент:
                <strong>
                    @if($orderItem->item_type === 'ticket')
                        Билет {{ $orderItem->ticket?->number ?? '[удалён]' }}
                    @else
                        Развлечение "{{ $orderItem->entertainment?->name ?? '[удалён]' }}"
                        ({{ $orderItem->quantity }} шт.)
                    @endif
                </strong>
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-4xl">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.order-items.update', $orderItem) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Заказ — можно менять -->
                <div class="mb-6">
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Заказ <span class="text-red-500">*</span>
                    </label>
                    <select name="order_id" id="order_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">— выберите заказ —</option>
                        @foreach($orders as $o)
                            <option value="{{ $o->id }}"
                                {{ old('order_id', $orderItem->order_id) == $o->id ? 'selected' : '' }}>
                                #{{ $o->id }} — {{ $o->user->email ?? 'Без пользователя' }}
                                ({{ $o->status }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Тип элемента — теперь можно менять! -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Тип элемента <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-8">
                        <label class="flex items-center">
                            <input type="radio" name="item_type" value="ticket"
                                   class="mr-3 text-blue-600 focus:ring-blue-500"
                                {{ old('item_type', $orderItem->item_type) === 'ticket' ? 'checked' : '' }}>
                            <span class="text-lg">Билет</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="entertainment"
                                   class="mr-3 text-blue-600 focus:ring-blue-500"
                                {{ old('item_type', $orderItem->item_type) === 'entertainment' ? 'checked' : '' }}>
                            <span class="text-lg">Развлечение</span>
                        </label>
                    </div>
                    @error('item_type')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Билет — показывается только при выборе типа "ticket" -->
                <div class="mb-6 ticket-section {{ old('item_type', $orderItem->item_type) === 'ticket' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Выберите билет <span class="text-red-500">*</span>
                    </label>
                    <select name="ticket_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">— выберите билет —</option>
                        @foreach($tickets as $t)
                            <option value="{{ $t->id }}"
                                {{ old('ticket_id', $orderItem->ticket_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->number }}
                                — {{ $t->voyage->name ?? 'Без рейса' }}
                                — {{ number_format($t->price, 0, '', ' ') }} ₽
                                @if($t->status !== 'Доступно') <em class="text-red-500">({{ $t->status }})</em> @endif
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Развлечение — показывается только при выборе типа "entertainment" -->
                <div class="mb-6 entertainment-section {{ old('item_type', $orderItem->item_type) === 'entertainment' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Развлечение <span class="text-red-500">*</span>
                            </label>
                            <select name="entertainment_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">— выберите —</option>
                                @foreach($entertainments as $e)
                                    <option value="{{ $e->id }}"
                                        {{ old('entertainment_id', $orderItem->entertainment_id) == $e->id ? 'selected' : '' }}>
                                        {{ $e->name }} — {{ number_format($e->price, 0, '', ' ') }} ₽
                                    </option>
                                @endforeach
                            </select>
                            @error('entertainment_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Количество <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="quantity" min="1" value="{{ old('quantity', $orderItem->quantity) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                {{ old('item_type', $orderItem->item_type) === 'ticket' ? 'readonly' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.orders.edit', $orderItem->order_id) }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-8 rounded-lg transition">
                        ← Вернуться к заказу
                    </a>
                </div>
            </form>

            <!-- Опасная зона -->
            <div class="mt-10 pt-8 border-t-2 border-red-200">
                <h3 class="text-lg font-bold text-red-700 mb-4">Опасная зона</h3>
                <p class="text-gray-700 mb-4">
                    Удаление элемента вернёт билет в статус "Доступно" (если это билет).
                </p>
                <form action="{{ route('admin.order-items.destroy', $orderItem) }}" method="POST"
                      onsubmit="return confirm('Точно удалить элемент #{{ $orderItem->id }}? Это действие нельзя отменить.');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition">
                        Удалить элемент
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Показываем нужный блок при смене типа
        document.querySelectorAll('input[name="item_type"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const isTicket = this.value === 'ticket';
                document.querySelector('.ticket-section').classList.toggle('hidden', !isTicket);
                document.querySelector('.entertainment-section').classList.toggle('hidden', isTicket);
            });
        });

        // Инициализация при загрузке
        const currentType = "{{ old('item_type', $orderItem->item_type) }}";
        if (currentType === 'ticket') {
            document.querySelector('.ticket-section').classList.remove('hidden');
            document.querySelector('.entertainment-section').classList.add('hidden');
        } else {
            document.querySelector('.ticket-section').classList.add('hidden');
            document.querySelector('.entertainment-section').classList.remove('hidden');
        }
    </script>
@endsection
