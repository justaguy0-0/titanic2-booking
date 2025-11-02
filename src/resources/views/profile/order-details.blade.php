@extends('head')

@section('title', 'Детали заказа #' . ($order->id ?? ''))

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Детали заказа #{{ $order->id ?? '' }}</h2>
                <a href="{{ route('profile.orders') }}" class="btn-back">← Назад к заказам</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(isset($order))
                {{-- Информация о заказе --}}
                <div class="section-card mb-4">
                    <div class="section-header">
                        <h5 class="mb-0">Информация о заказе</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="order-detail-item">
                                    <div class="detail-label">Номер заказа</div>
                                    <div class="detail-value">#{{ $order->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order-detail-item">
                                    <div class="detail-label">Дата оформления</div>
                                    <div class="detail-value">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order-detail-item">
                                    <div class="detail-label">Статус</div>
                                    <div class="detail-value">
                                        <span class="status-badge 
                                            @if($order->status === 'Новый') status-new
                                            @elseif($order->status === 'Обработан') status-processing
                                            @elseif($order->status === 'Оплачен') status-paid
                                            @elseif($order->status === 'Отправлен') status-sent
                                            @elseif($order->status === 'Отменён') status-cancelled
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order-detail-item">
                                    <div class="detail-label">Общая сумма</div>
                                    <div class="detail-value order-total">{{ number_format($order->total_price, 2) }} ₽</div>
                                </div>
                            </div>
                        </div>

                        @if(in_array($order->status, ['Новый', 'Обработан']))
                            <div class="mt-4">
                                <form action="{{ route('profile.orders.cancel', $order->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Вы уверены, что хотите отменить заказ?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-danger">
                                        Отменить заказ
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Состав заказа --}}
                @if(isset($order->orderItems) && $order->orderItems->count() > 0)
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">Состав заказа</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>Позиция</th>
                                            <th>Тип</th>
                                            <th>Цена</th>
                                            <th>Количество</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="item-info">
                                                        @if($item->type === 'ticket' && isset($item->ticket))
                                                            <div class="item-name">{{ $item->ticket->type ?? 'Билет' }}</div>
                                                            <div class="item-details">Билет № {{ $item->ticket->number ?? '' }}</div>
                                                            @if(isset($item->ticket->voyage))
                                                                <div class="item-voyage">{{ $item->ticket->voyage->name ?? '' }}</div>
                                                                @if(isset($item->ticket->voyage->placeDeparture) && isset($item->ticket->voyage->icebergArrival))
                                                                    <div class="item-route">
                                                                        {{ $item->ticket->voyage->placeDeparture->name ?? '' }} → 
                                                                        {{ $item->ticket->voyage->icebergArrival->name ?? '' }}
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @elseif($item->type === 'entertainment' && isset($item->entertainment))
                                                            <div class="item-name">{{ $item->entertainment->name ?? 'Развлечение' }}</div>
                                                            <div class="item-details">Развлечение</div>
                                                        @else
                                                            <span class="item-deleted">Позиция удалена</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="type-badge 
                                                        @if($item->type === 'ticket') type-ticket
                                                        @else type-entertainment
                                                        @endif">
                                                        @if($item->type === 'ticket') Билет
                                                        @else Развлечение
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="item-price">{{ number_format($item->price, 2) }} ₽</td>
                                                <td class="item-quantity">{{ $item->quantity }} шт.</td>
                                                <td class="item-total">{{ number_format($item->price * $item->quantity, 2) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end total-label">Итого:</td>
                                            <td class="total-value">{{ number_format($order->total_price, 2) }} ₽</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="section-card">
                    <div class="card-body text-center py-5">
                        <p class="empty-message">Заказ не найден.</p>
                        <a href="{{ route('profile.orders') }}" class="btn-submit mt-3">Вернуться к заказам</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
.order-detail-item {
    padding: 1rem;
    background: #0f172a;
    border-radius: 0.375rem;
    border: 1px solid #334155;
}

.detail-label {
    font-size: 0.875rem;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: #fcd34d;
}

.order-total {
    font-size: 1.25rem;
    color: #fbbf24;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 600;
    color: #fcd34d;
}

.item-details {
    font-size: 0.75rem;
    color: #94a3b8;
}

.item-voyage {
    font-size: 0.875rem;
    color: #fbbf24;
    font-weight: 500;
    margin-top: 0.25rem;
}

.item-route {
    font-size: 0.75rem;
    color: #64748b;
}

.item-deleted {
    color: #64748b;
    font-style: italic;
}

.type-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.type-ticket {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.type-entertainment {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.item-price,
.item-quantity {
    color: #94a3b8;
}

.item-total {
    font-weight: 600;
    color: #fcd34d;
}

.orders-table tfoot {
    background: #0f172a;
    border-top: 2px solid #fbbf24;
}

.orders-table tfoot td {
    padding: 1.25rem 1.5rem;
    font-weight: 600;
}

.total-label {
    color: #fbbf24;
    font-size: 1rem;
}

.total-value {
    color: #fbbf24;
    font-size: 1.25rem;
    font-weight: 700;
}

.status-new {
    background: #3b82f6;
    color: white;
}

.status-processing {
    background: #eab308;
    color: #1e293b;
}

.status-paid {
    background: #10b981;
    color: white;
}

.status-sent {
    background: #8b5cf6;
    color: white;
}

.status-cancelled {
    background: #ef4444;
    color: white;
}

@media (max-width: 767.98px) {
    .orders-table td {
        font-size: 0.875rem;
        padding: 0.75rem;
    }
    
    .item-info {
        font-size: 0.875rem;
    }
}
</style>
@endsection