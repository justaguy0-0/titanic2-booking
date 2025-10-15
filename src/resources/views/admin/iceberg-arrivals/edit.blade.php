@extends('admin.admin')

@section('title', 'Редактировать айсберг')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать айсберг</h1>
            <p class="text-gray-600 mt-2">Измените данные и сохраните изменения</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.iceberg-arrivals.update', $icebergArrival) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Название айсберга <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $icebergArrival->name ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Например: Тот самый айсберг из фильма"
                        required
                    >

                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-gray-500 text-xs mt-1">Максимум 100 символов. Название должно быть уникальным.</p>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <strong>ID:</strong> {{ $icebergArrival->id ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Создано:</strong> {{ $icebergArrival->created_at->format('d.m.Y H:i') ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Обновлено:</strong> {{ $icebergArrival->updated_at->format('d.m.Y H:i') ?? 'N/A' }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Сохранить изменения
                    </button>

                    <a
                        href="{{ route('admin.iceberg-arrivals.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Опасная зона</h3>
                <p class="text-sm text-gray-600 mb-3">Удаление айсберга нельзя отменить.</p>

                <form action="{{ route('admin.iceberg-arrivals.destroy', $icebergArrival) }}"
                      method="POST"
                      onsubmit="return confirm('Вы уверены, что хотите удалить этот айсберг? Это действие нельзя отменить!')">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Удалить айсберг
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
