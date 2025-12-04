@extends('admin.admin')
@section('title', 'Платежи')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Платежи</h1>
            <a href="{{ route('admin.payments.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Добавить платёж
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
                        <a href="{{ $sortUrl('order_id') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Заказ <span class="text-gray-400">{{ $sortIcon('order_id') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('amount') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Сумма <span class="text-gray-400">{{ $sortIcon('amount') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('provider') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Провайдер <span class="text-gray-400">{{ $sortIcon('provider') }}</span>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ $sortUrl('status') }}" class="hover:text-gray-900 flex items-center gap-1">
                            Статус <span class="text-gray-400">{{ $sortIcon('status') }}</span>
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
                @forelse($payments as $payment)
                    @php
                        $statusClass = match($payment->status) {
                            'Успешно' => 'bg-green-100 text-green-800',
                            'Отклонено' => 'bg-red-100 text-red-800',
                            default => 'bg-yellow-100 text-yellow-800',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    #{{ $payment->order->id }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($payment->amount, 0, '', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $payment->provider }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->created_at->format('d.m.Y H:i') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                            <a href="{{ route('admin.payments.edit', $payment) }}"
                               class="text-gray-600 hover:text-indigo-600" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Удалить платёж #{{ $payment->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-600 hover:text-red-600" title="Удалить">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Нет платежей.
                            <a href="{{ route('admin.payments.create') }}" class="text-blue-600 hover:underline">
                                Добавить первый?
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
