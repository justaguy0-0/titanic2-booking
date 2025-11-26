@extends('admin.admin')

@section('title', 'Развлечения')

@section('content')
    <div class="container mx-auto px-4 py-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Развлечения</h1>
            <a href="{{ route('admin.entertainments.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить развлечение
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

                        $sortUrl = fn($field) => request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => $currentSort === $field ? $nextDir : 'asc'
                        ]);

                        $sortIcon = fn($field) => $currentSort === $field
                            ? ($currentDir === 'asc' ? '↑' : '↓')
                            : '';
                    @endphp

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            ID <span class="text-gray-400">{{ $sortIcon('id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('name') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Название <span class="text-gray-400">{{ $sortIcon('name') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('price') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Цена <span class="text-gray-400">{{ $sortIcon('price') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('created_at') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Создано <span class="text-gray-400">{{ $sortIcon('created_at') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($entertainments as $entertainment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $entertainment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $entertainment->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($entertainment->price, 0, '', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $entertainment->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.entertainments.edit', $entertainment) }}"
                               class="text-blue-600 hover:text-blue-900">Редактировать</a>

                            <form action="{{ route('admin.entertainments.destroy', $entertainment) }}"
                                  method="POST" class="inline"
                                  onsubmit="return {{ $entertainment->order_items_count > 0 ? 'false' : 'confirm(\"Удалить «'.addslashes($entertainment->name).'»?\")' }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900
                   @if($entertainment->order_items_count > 0) opacity-50 cursor-not-allowed @endif"
                                        {{ $entertainment->order_items_count > 0 ? 'disabled' : '' }}
                                        title="{{ $entertainment->order_items_count > 0 ? 'Используется в ' . $entertainment->order_items_count . ' заказ(е/ах)' : '' }}">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Нет развлечений.
                            <a href="{{ route('admin.entertainments.create') }}" class="text-blue-600 hover:underline">
                                Добавить первое?
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $entertainments->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
