@extends('admin.admin')
@section('title', 'Типы кают')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Типы кают</h1>
            <a href="{{ route('admin.cabin-types.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить тип каюты
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
                        $currentSort = request('sort', 'name');
                        $currentDir = request('direction', 'asc');
                        $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
                        $sortUrl = function($field) use ($currentSort, $nextDir) {
                            $dir = $currentSort === $field ? $nextDir : 'asc';
                            return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $dir]);
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
                        Описание
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
                @forelse($cabinTypes as $cabinType)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $cabinType->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $cabinType->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-md truncate">
                            {{ $cabinType->description ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cabinType->created_at->format('d.m.Y H:i') }}
                        </td>

                        {{-- Только иконки в действиях --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.cabin-types.edit', $cabinType) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.cabin-types.destroy', $cabinType) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Удалить тип каюты «{{ addslashes($cabinType->name) }}»?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-gray-600 hover:text-red-600
                                               @if($cabinType->tickets()->exists()) opacity-40 cursor-not-allowed @endif"
                                        @if($cabinType->tickets()->exists()) disabled title="Нельзя удалить — есть билеты" @endif>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Нет типов кают.
                            <a href="{{ route('admin.cabin-types.create') }}" class="text-blue-600 hover:underline">
                                Добавить первый?
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $cabinTypes->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
