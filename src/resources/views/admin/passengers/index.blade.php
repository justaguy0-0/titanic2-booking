@extends('admin.admin')
@section('title', 'Пассажиры')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Все пассажиры</h1>
        </div>

        <form method="GET" class="mb-6 flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Поиск по имени, фамилии или паспорту..."
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-96">
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Найти
            </button>
            @if(request('search'))
                <a href="{{ route('admin.passengers.index') }}"
                   class="px-4 py-2 text-gray-600 hover:underline">
                    Очистить
                </a>
            @endif
        </form>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    @php
                        $currentSort = request('sort', 'id');
                        $currentDir  = request('direction', 'desc');
                        $nextDir     = $currentDir === 'asc' ? 'desc' : 'asc';

                        $sortUrl = function($field) use ($currentSort, $nextDir) {
                            return route('admin.passengers.index') . '?' . http_build_query(
                                array_merge(request()->query(), [
                                    'sort'      => $field,
                                    'direction' => $currentSort === $field ? $nextDir : 'asc'
                                ])
                            );
                        };

                        $sortIcon = function($field) use ($currentSort, $currentDir) {
                            if ($currentSort !== $field) return '';
                            return $currentDir === 'asc' ? '↑' : '↓';
                        };
                    @endphp

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ID <span class="text-gray-400">{{ $sortIcon('id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('first_name') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ФИО <span class="text-gray-400">{{ $sortIcon('first_name') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('birth_date') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Возраст <span class="text-gray-400">{{ $sortIcon('birth_date') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Паспорт</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Гражданство</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Заказ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Создан <span class="text-gray-400">{{ $sortIcon('created_at') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($passengers as $passenger)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $passenger->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $passenger->full_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $passenger->age }} лет
                            @if($passenger->discount_percent > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-1">
                                    -{{ $passenger->discount_percent }}%
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $passenger->passport_series }} {{ $passenger->passport_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $passenger->citizenship }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($passenger->orderItem && $passenger->orderItem->order)
                                <a href="{{ route('admin.orders.show', $passenger->orderItem->order) }}"
                                   class="text-blue-600 hover:underline">
                                    #{{ $passenger->orderItem->order->id }}
                                </a>
                                <br>
                                <span class="text-xs text-gray-500">
                                    {{ $passenger->orderItem->order->user->name ?? 'Нет пользователя' }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">нет заказа</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $passenger->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.passengers.show', $passenger) }}"
                               class="text-gray-600 hover:text-blue-600" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.passengers.edit', $passenger) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.passengers.destroy', $passenger) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Удалить пассажира «{{ addslashes($passenger->full_name) }}»?')"
                                        class="text-gray-600 hover:text-red-600" title="Удалить">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Пассажиров не найдено
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $passengers->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
