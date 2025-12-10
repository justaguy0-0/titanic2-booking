@extends('admin.admin')
@section('title', 'Билеты')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Билеты</h1>
            <a href="{{ route('admin.tickets.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить билет
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
                        Рейс
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Тип каюты
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('number') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Номер <span class="text-gray-400">{{ $sortIcon('number') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('price') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Цена <span class="text-gray-400">{{ $sortIcon('price') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('status') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Статус <span class="text-gray-400">{{ $sortIcon('status') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Заказ
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    @php
                        $isSold = $ticket->status === 'Продано';
                        $isBooked = in_array($ticket->status, ['Забронировано', 'Забронирован']);
                        $rowClass = $isSold ? 'bg-red-50' : ($isBooked ? 'bg-yellow-50' : '');
                    @endphp
                    <tr class="{{ $rowClass }} hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.voyages.show', $ticket->voyages_id) }}" class="text-blue-600 hover:underline">
                                {{ $ticket->voyage->name ?? '—' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $ticket->cabinType->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">
                            {{ $ticket->number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ number_format($ticket->price, 0, '', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($isSold) bg-red-100 text-red-800
                                @elseif($isBooked) bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($ticket->orderItems->isNotEmpty())
                                <a href="{{ route('admin.orders.show', $ticket->orderItems->first()->order_id) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    #{{ $ticket->orderItems->first()->order_id }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                               class="text-gray-600 hover:text-blue-600" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.tickets.edit', $ticket) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if($ticket->orderItems->isEmpty())
                                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Удалить билет {{ $ticket->number }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300" title="Нельзя удалить — билет в заказе">
                                    <i class="fas fa-trash-alt"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Нет билетов. <a href="{{ route('admin.tickets.create') }}" class="text-blue-600 hover:underline">Добавить первый?</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
