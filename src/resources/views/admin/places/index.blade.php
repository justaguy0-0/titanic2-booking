@extends('admin.admin')

@section('title', 'Места')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Места ({{ $type === 'departure' ? 'Отправления' : 'Прибытия' }})</h1>
            <a href="{{ route('admin.places.create', ['type' => $type]) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить место
            </a>
        </div>

        <div class="tabs mb-4">
            <a href="{{ route('admin.places.index', ['type' => 'departure']) }}"
               class="px-4 py-2 mr-2 {{ $type === 'departure' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                Отправления
            </a>
            <a href="{{ route('admin.places.index', ['type' => 'arrival']) }}"
               class="px-4 py-2 {{ $type === 'arrival' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                Прибытия
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Название
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Тип
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Дата создания
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($places as $place)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $place->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $place->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $place->type === 'departure' ? 'Отправление' : 'Прибытие' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $place->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.places.edit', $place) }}"
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                Редактировать
                            </a>
                            <form action="{{ route('admin.places.destroy', $place) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить это место?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Нет мест {{ $type === 'departure' ? 'отправления' : 'прибытия' }}.
                            <a href="{{ route('admin.places.create', ['type' => $type]) }}" class="text-blue-600">Добавить первое?</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $places->links() }}
        </div>
    </div>
@endsection
