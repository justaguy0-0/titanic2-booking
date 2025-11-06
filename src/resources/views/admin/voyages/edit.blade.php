@extends('admin.admin')

@section('title', 'Редактировать путешествие')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Редактировать путешествие</h1>
        <p class="text-gray-600 mt-2">Измените данные и сохраните изменения</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('admin.voyages.update', $voyage) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $voyage->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="Например: Путешествие к айсбергу" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="departure_place_id" class="block text-sm font-medium text-gray-700 mb-2">Место отправления <span class="text-red-500">*</span></label>
                <select name="departure_place_id" id="departure_place_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('departure_place_id') border-red-500 @enderror"
                        required>
                    <option value="">Выберите место отправления</option>
                    @foreach($departures as $place)
                        <option value="{{ $place->id }}" {{ old('departure_place_id', $voyage->departure_place_id) == $place->id ? 'selected' : '' }}>
                            {{ $place->name }}
                        </option>
                    @endforeach
                </select>
                @error('departure_place_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="arrival_place_id" class="block text-sm font-medium text-gray-700 mb-2">Место прибытия <span class="text-red-500">*</span></label>
                <select name="arrival_place_id" id="arrival_place_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('arrival_place_id') border-red-500 @enderror"
                        required>
                    <option value="">Выберите место прибытия</option>
                    @foreach($arrivals as $place)
                        <option value="{{ $place->id }}" {{ old('arrival_place_id', $voyage->arrival_place_id) == $place->id ? 'selected' : '' }}>
                            {{ $place->name }}
                        </option>
                    @endforeach
                </select>
                @error('arrival_place_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">Дата отправления <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="departure_date" id="departure_date" value="{{ old('departure_date', $voyage->departure_date) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('departure_date') border-red-500 @enderror"
                       required>
                @error('departure_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="arrival_date" class="block text-sm font-medium text-gray-700 mb-2">Дата прибытия <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $voyage->arrival_date) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('arrival_date') border-red-500 @enderror"
                       required>
                @error('arrival_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="travel_time" class="block text-sm font-medium text-gray-700 mb-2">Время в пути (часы) <span class="text-red-500">*</span></label>
                <input type="number" name="travel_time" id="travel_time" value="{{ old('travel_time', $voyage->travel_time) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('travel_time') border-red-500 @enderror"
                       required>
                @error('travel_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Базовая цена (руб) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="base_price" id="base_price" value="{{ old('base_price', $voyage->base_price) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('base_price') border-red-500 @enderror"
                       required>
                @error('base_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.voyages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Отмена
                </a>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
            <p class="text-sm text-gray-600 mb-3">Удаление путешествия нельзя отменить.</p>
            <form action="{{ route('admin.voyages.destroy', $voyage) }}" method="POST"
                  onsubmit="return confirm('Вы уверены, что хотите удалить это путешествие? Это действие нельзя отменить!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Удалить путешествие
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
