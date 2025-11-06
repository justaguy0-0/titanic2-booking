@extends('admin.admin')

@section('title', 'Редактировать билет')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать билет #{{ $ticket->number }}</h1>
            <p class="text-gray-600 mt-2">Обновите данные билета и сохраните изменения</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="voyages_id" class="block text-sm font-medium text-gray-700 mb-2">Рейс <span class="text-red-500">*</span></label>
                    <select name="voyages_id" id="voyages_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('voyages_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите рейс</option>
                        @foreach($voyages as $voyage)
                            <option value="{{ $voyage->id }}" {{ old('voyages_id', $ticket->voyages_id) == $voyage->id ? 'selected' : '' }}>
                                {{ $voyage->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('voyages_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="cabin_type_id" class="block text-sm font-medium text-gray-700 mb-2">Тип каюты <span class="text-red-500">*</span></label>
                    <select name="cabin_type_id" id="cabin_type_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cabin_type_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите тип каюты</option>
                        @foreach($cabinTypes as $cabinType)
                            <option value="{{ $cabinType->id }}" data-price="{{ $cabinType->base_price }}" {{ old('cabin_type_id', $ticket->cabin_type_id) == $cabinType->id ? 'selected' : '' }}>
                                {{ $cabinType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cabin_type_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-2">Номер билета <span class="text-red-500">*</span></label>
                    <input type="text" name="number" id="number" value="{{ old('number', $ticket->number) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('number') border-red-500 @enderror"
                           placeholder="Например: TIT1234" required>
                    @error('number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Цена (руб) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $ticket->price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                           readonly>
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($ticket->orderItems->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-red-500 text-sm">Билет связан с заказом #{{ $ticket->orderItems->first()->order_id }}. Статус нельзя изменить.</p>
                        <input type="hidden" name="status" value="{{ $ticket->status }}">
                    </div>
                @else
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="Доступно" {{ old('status', $ticket->status) == 'Доступно' ? 'selected' : '' }}>Доступно</option>
                            <option value="Забронировано" {{ old('status', $ticket->status) == 'Забронировано' ? 'selected' : '' }}>Забронировано</option>
                            <option value="Продано" {{ old('status', $ticket->status) == 'Продано' ? 'selected' : '' }}>Продано</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('cabin_type_id').addEventListener('change', function () {
                const priceInput = document.getElementById('price');
                const selectedOption = this.options[this.selectedIndex];
                const basePrice = selectedOption.getAttribute('data-price') || 0;
                priceInput.value = parseFloat(basePrice).toFixed(2);
            });
        </script>
    @endsection
@endsection
