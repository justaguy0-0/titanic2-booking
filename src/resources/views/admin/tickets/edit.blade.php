@extends('admin.admin')

@section('title', 'Редактировать билет')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Редактировать билет</h1>
        <p class="text-gray-600 mt-2">Измените данные и сохраните изменения</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="voyages_id" class="block text-sm font-medium text-gray-700 mb-2">Путешествие <span class="text-red-500">*</span></label>
                <select name="voyages_id" id="voyages_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('voyages_id') border-red-500 @enderror"
                        required>
                    <option value="">Выберите путешествие</option>
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
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Тип <span class="text-red-500">*</span></label>
                <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                        required>
                    <option value="">Выберите тип</option>
                    <option value="Первый класс" {{ old('type', $ticket->type) == 'Первый класс' ? 'selected' : '' }}>Первый класс</option>
                    <option value="Второй класс" {{ old('type', $ticket->type) == 'Второй класс' ? 'selected' : '' }}>Второй класс</option>
                    <option value="Третий класс" {{ old('type', $ticket->type) == 'Третий класс' ? 'selected' : '' }}>Третий класс</option>
                    <option value="Люкс" {{ old('type', $ticket->type) == 'Люкс' ? 'selected' : '' }}>Люкс</option>
                </select>
                @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Выберите один из доступных типов билета.</p>
            </div>

            <div class="mb-6">
                <label for="number" class="block text-sm font-medium text-gray-700 mb-2">Номер <span class="text-red-500">*</span></label>
                <input type="text" name="number" id="number" value="{{ old('number', $ticket->number) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('number') border-red-500 @enderror"
                       placeholder="Например: TICK-001" required>
                @error('number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Цена (руб) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $ticket->price) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                       placeholder="Например: 1500.50" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

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

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.tickets.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Отмена
                </a>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
            <p class="text-sm text-gray-600 mb-3">Удаление билета нельзя отменить.</p>
            <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST"
                  onsubmit="return confirm('Вы уверены, что хотите удалить этот билет? Это действие нельзя отменить!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Удалить билет
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
