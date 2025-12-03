@extends('admin.admin')
@section('title', 'Путешествия')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Рейсы</h1>
            <a href="{{ route('admin.voyages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Рейс
            </a>
        </div>

        {{-- Уведомления --}}
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
                        $currentSort = request('sort', 'departure_date');
                        $currentDir = request('direction', 'desc');
                        $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
                        $sortUrl = fn($field) => request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => $currentSort === $field ? $nextDir : 'asc'
                        ]);
                        $sortIcon = fn($field) => $currentSort === $field
                            ? ($currentDir === 'asc' ? '↑' : '↓')
                            : '';
                    @endphp

                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ID <span class="text-gray-400">{{ $sortIcon('id') }}</span>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('name') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Название <span class="text-gray-400">{{ $sortIcon('name') }}</span>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Откуда</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Куда</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('departure_date') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Дата отправления <span class="text-gray-400">{{ $sortIcon('departure_date') }}</span>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('arrival_date') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Дата прибытия <span class="text-gray-400">{{ $sortIcon('arrival_date') }}</span>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($voyages as $voyage)
                    @php
                        $isPast = $voyage->arrival_date?->isPast() || $voyage->departure_date?->isPast();
                        $rowClasses = $isPast ? 'opacity-60 italic' : 'hover:bg-gray-50 transition';
                    @endphp
                    <tr class="{{ $rowClasses }}">
                        <td class="px-4 py-4 text-sm text-gray-900">{{ $voyage->id }}</td>

                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            @if($isPast)
                                <i class="fas fa-calendar-times text-red-400 mr-2" title="Рейс завершён"></i>
                            @endif
                            {{ $voyage->name }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-900">
                            {{ $voyage->departurePlace->name ?? '—' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900">
                            {{ $voyage->arrivalPlace->name ?? '—' }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $voyage->departure_date?->format('d.m.Y H:i') ?? '—' }}
                            @if($voyage->departure_date?->isToday())
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Сегодня
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $voyage->arrival_date?->format('d.m.Y H:i') ?? '—' }}
                        </td>

                        <td class="px-4 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.voyages.show', $voyage) }}"
                               class="text-gray-600 hover:text-blue-600" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.voyages.edit', $voyage) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if(! $voyage->departure_date?->isPast())
                                <form action="{{ route('admin.voyages.destroy', $voyage) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Удалить рейс «{{ addslashes($voyage->name) }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300" title="Нельзя удалить завершённый рейс">
                                    <i class="fas fa-trash-alt"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Нет рейсов. <a href="{{ route('admin.voyages.create') }}" class="text-blue-600 hover:underline">Добавить первый?</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $voyages->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
