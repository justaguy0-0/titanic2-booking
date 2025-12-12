@extends('admin.admin')
@section('title', 'Редактировать пассажира')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать пассажира</h1>
            <p class="text-gray-600 mt-2">
                ID: {{ $passenger->id }} | {{ $passenger->full_name }} | Создан: {{ $passenger->created_at->format('d.m.Y H:i') }}
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl">
            <form action="{{ route('admin.passengers.update', $passenger) }}" method="POST">
                @csrf @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $passenger->first_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Фамилия</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $passenger->last_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата рождения</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $passenger->birth_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Текущий возраст: {{ $passenger->age }} лет</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Серия паспорта</label>
                            <input type="text" name="passport_series" value="{{ old('passport_series', $passenger->passport_series) }}"
                                   placeholder="1234"
                                   maxlength="10"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('passport_series') border-red-500 @enderror">
                            @error('passport_series') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Номер паспорта</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number', $passenger->passport_number) }}"
                                   placeholder="567890"
                                   maxlength="20"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('passport_number') border-red-500 @enderror">
                            @error('passport_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Гражданство</label>
                        <input type="text" name="citizenship" value="{{ old('citizenship', $passenger->citizenship) }}"
                               placeholder="Россия"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('citizenship') border-red-500 @enderror">
                        @error('citizenship') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($passenger->discount_percent > 0)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Применена детская скидка: {{ $passenger->discount_percent }}%</strong>
                                <br>
                                <span class="text-xs">Скидка пересчитается автоматически при изменении даты рождения</span>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                            class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.passengers.show', $passenger) }}"
                       class="px-8 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-red-700 mb-4">Опасная зона</h3>
                <form action="{{ route('admin.passengers.destroy', $passenger) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Удалить пассажира «{{ addslashes($passenger->full_name) }}» навсегда? Это также удалит связанные данные о билете.')"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                        Удалить пассажира
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
