@extends('admin.admin')
@section('title', 'Добавить элемент заказа')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Добавить элемент заказа</h1>
        <p class="text-gray-600 mb-6">Выберите заказ и **либо билет, либо развлечение**.</p>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
            <form action="{{ route('admin.order-items.store') }}" method="POST">
                @csrf

                <!-- Заказ -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Заказ <span class="text-red-500">*</span>
                    </label>
                    <select name="order_id" required
                            class="w-full px-4 py-2 border rounded-lg @error('order_id') border-red-500 @enderror">
                        <option value="">— выберите —</option>
                        @foreach($orders as $o)
                            <option value="{{ $o->id }}" {{ old('order_id')==$o->id?'selected':'' }}>
                                #{{ $o->id }} ({{ $o->user->email ?? '—' }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Тип элемента -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Тип <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-6">
                        <label><input type="radio" name="type" value="ticket" checked class="mr-2"> Билет</label>
                        <label><input type="radio" name="type" value="entertainment" class="mr-2"> Развлечение</label>
                    </div>
                    @error('type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Билет (показывается только если выбран тип ticket) -->
                <div class="mb-6 ticket-section">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Билет <span class="text-red-500">*</span>
                    </label>
                    <select name="ticket_id"
                            class="w-full px-4 py-2 border rounded-lg @error('ticket_id') border-red-500 @enderror">
                        <option value="">— выберите —</option>
                        @foreach($tickets as $t)
                            <option value="{{ $t->id }}" {{ old('ticket_id')==$t->id?'selected':'' }}>
                                {{ $t->number }} ({{ $t->voyage->name ?? '—' }}, {{ number_format($t->price,2,',',' ') }} ₽)
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Развлечение (показывается только если выбран тип entertainment) -->
                <div class="mb-6 entertainment-section hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Развлечение <span class="text-red-500">*</span>
                    </label>
                    <select name="entertainment_id"
                            class="w-full px-4 py-2 border rounded-lg @error('entertainment_id') border-red-500 @enderror">
                        <option value="">— выберите —</option>
                        @foreach($entertainments as $e)
                            <option value="{{ $e->id }}" {{ old('entertainment_id')==$e->id?'selected':'' }}>
                                {{ $e->name }} – {{ number_format($e->price,2,',',' ') }} ₽
                            </option>
                        @endforeach
                    </select>
                    @error('entertainment_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

                    <label class="block mt-3 text-sm font-medium text-gray-700">
                        Кол‑во
                    </label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity',1) }}"
                           class="w-24 px-3 py-2 border rounded-lg">
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.order-items.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Показ/скрытие полей в зависимости от выбранного типа
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelector('.ticket-section').classList.toggle('hidden', this.value !== 'ticket');
                document.querySelector('.entertainment-section').classList.toggle('hidden', this.value !== 'entertainment');
            });
        });
        // Инициализация при загрузке
        document.querySelector('input[name="type"]:checked').dispatchEvent(new Event('change'));
    </script>
@endsection
