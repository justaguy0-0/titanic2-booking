@extends('head')

@section('title', 'Мои заказы')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <h2 class="page-title mb-4">Мои заказы</h2>

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

            <div class="orders-section">
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>№ Заказа</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="order-id">#{{ $order->id }}</td>
                                            <td class="order-date">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                            <td class="order-price">{{ number_format($order->total_price, 2) }} ₽</td>
                                            <td>
                                                <span class="status-badge status-{{ strtolower($order->status) }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('profile.orders.show', $order->id) }}" class="action-link">
                                                    Подробнее
                                                </a>
                                                @if(in_array($order->status, ['Новый', 'Обработан']))
                                                    <form action="{{ route('profile.orders.cancel', $order->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Вы уверены, что хотите отменить заказ?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-link cancel-link">Отменить</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <p class="empty-message">У вас пока нет заказов.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection