@extends('admin.admin')

@section('title', 'Редактировать развлечение')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Редактировать развлечение</h1>
        <p class="text-gray-600 mt-2">Измените данные и сохраните изменения</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('admin.entertainments.update', $entertainment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $entertainment->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="Например: Круиз на айсберге" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Цена (руб) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $entertainment->price) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                       placeholder="Например: 500.00" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.entertainments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Отмена
                </a>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
            <p class="text-sm text-gray-600 mb-3">Удаление развлечения нельзя отменить.</p>
            <form action="{{ route('admin.entertainments.destroy', $entertainment) }}" method="POST"
                  onsubmit="return confirm('Вы уверены, что хотите удалить это развлечение? Это действие нельзя отменить!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Удалить развлечение
                </button>
            </form>
        </div>
    </div>
</div>
@endsection