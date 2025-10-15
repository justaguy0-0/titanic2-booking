@extends('admin.admin')

@section('title', 'Добавить место отправления')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Добавить место прибытия</h1>
        <p class="text-gray-600 mt-2">Заполните форму для добавления нового места</p>
    </div>


    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('admin.iceberg-arrival.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Название места <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="Например: Москва, Санкт-Петербург"
                    required
                >
                
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <p class="text-gray-500 text-xs mt-1">Максимум 100 символов. Название должно быть уникальным.</p>
            </div>

            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Сохранить
                </button>
                
                <a 
                    href="{{ route('admin.place-departures.index') }}" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection