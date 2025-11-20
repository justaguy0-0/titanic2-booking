@extends('admin.admin')
@section('title', 'Места')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Места ({{ $type === 'departure' ? 'Отправления' : 'Прибытия' }})
            </h1>
            <a href="{{ route('admin.places.create', ['type' => $type]) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить место
            </a>
        </div>
        <div class="tabs mb-6 border-b border-gray-200">
            <a href="{{ route('admin.places.index', ['type' => 'departure']) }}"
               class="px-6 py-3 font-medium border-b-2 {{ $type === 'departure' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                Отправления
            </a>
            <a href="{{ route('admin.places.index', ['type' => 'arrival']) }}"
               class="px-6 py-3 font-medium border-b-2 {{ $type === 'arrival' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
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
                    @php
                        $currentSort = request('sort', 'id');
                        $currentDir = request('direction', 'desc');
                        $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
                        $sortUrl = function($field) use ($currentSort, $nextDir, $type) {
                            $dir = $currentSort === $field ? $nextDir : 'asc';
                            return route('admin.places.index', ['type' => $type, 'sort' => $field, 'direction' => $dir]);
                        };
                        $sortIcon = function($field) use ($currentSort, $currentDir) {
                            if ($currentSort !== $field) return '';
                            return $currentDir === 'asc' ? '↑' : '↓';
                        };
                    @endphp
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ID <span class="text-gray-400 text-lg">{{ $sortIcon('id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('name') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Название <span class="text-gray-400 text-lg">{{ $sortIcon('name') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Тип
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Дата создания <span class="text-gray-400 text-lg">{{ $sortIcon('created_at') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($places as $place)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $place->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $place->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $place->type === 'departure' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $place->type === 'departure' ? 'Отправление' : 'Прибытие' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $place->created_at->format('d.m.Y H:i') }}
                        </td>

                        {{-- Действия только иконки --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.places.edit', $place) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.places.destroy', $place) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Удалить место «{{ addslashes($place->name) }}»?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Нет мест {{ $type === 'departure' ? 'отправления' : 'прибытия' }}.
                            <a href="{{ route('admin.places.create', ['type' => $type]) }}" class="text-blue-600 hover:underline">
                                Добавить первое?
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $places->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
