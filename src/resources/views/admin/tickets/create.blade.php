@extends('admin.admin')
@section('title', 'Добавить билет')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Добавить билет</h1>
            <p class="text-gray-600 mt-2">Введите данные и сохраните новый билет</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="voyages_id" class="block text-sm font-medium text-gray-700 mb-2">Рейс <span class="text-red-500">*</span></label>
                    <select name="voyages_id" id="voyages_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('voyages_id') border-red-500 @enderror"
                            required>
                        <option value="">Выберите рейс</option>
                        @foreach($voyages as $voyage)
                            <option value="{{ $voyage->id }}" {{ old('voyages_id') == $voyage->id ? 'selected' : '' }}>
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
                            <option value="{{ $cabinType->id }}"
                                    data-price="{{ $cabinType->base_price }}"
                                {{ old('cabin_type_id') == $cabinType->id ? 'selected' : '' }}>
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
                    <input type="text" name="number" id="number" value="{{ old('number', 'TIT' . rand(1000, 9999)) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('number') border-red-500 @enderror"
                           placeholder="Например: TIT1234" required>
                    @error('number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Цена (руб) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                           min="0" required>
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="status" value="Доступно">

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

    @push('scripts')
        <script>
            const cabinSelect = document.getElementById('cabin_type_id');
            const priceInput = document.getElementById('price');

            // При загрузке — если есть old() или selected → установить цену
            function updatePrice() {
                const selected = cabinSelect.options[cabinSelect.selectedIndex];
                if (selected && selected.value) {
                    const basePrice = selected.getAttribute('data-price');
                    if (basePrice && !priceInput.dataset.manuallyEdited) {
                        priceInput.value = parseFloat(basePrice).toFixed(2);
                    }
                }
            }

            // При смене типа каюты — обновить цену (если не редактировалось вручную)
            cabinSelect.addEventListener('change', function () {
                updatePrice();
                priceInput.dataset.manuallyEdited = 'false';
            });

            // Если пользователь начал вводить вручную — больше не автозаполнять
            priceInput.addEventListener('input', function () {
                this.dataset.manuallyEdited = 'true';
            });

            // При загрузке страницы
            document.addEventListener('DOMContentLoaded', updatePrice);
        </script>
    @endpush
@endsection
