@extends('head')

@section('title', 'Оплата заказа')

@section('main_content')
<div class="container py-5 payment-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Сообщения об ошибках --}}
            @if (session('error'))
                <div class="alert payment-alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif

            {{-- Заголовок страницы --}}
            <div class="payment-header">
                <h1 class="payment-title">
                    <i class="fas fa-credit-card me-3"></i>Оплата заказа
                </h1>
                <p class="payment-subtitle">Пожалуйста, проверьте детали вашего заказа перед оплатой</p>
            </div>

            <div class="row g-4">
                {{-- Левая колонка: Информация о рейсе --}}
                <div class="col-lg-5">
                    <div class="payment-card mb-4">
                        <div class="payment-card-header">
                            <h3 class="payment-card-title">
                                <i class="fas fa-ship me-2"></i>Информация о рейсе
                            </h3>
                        </div>
                        <div class="payment-card-body">
                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-anchor payment-icon"></i>Рейс:
                                </span>
                                <span class="voyage-info-value">{{ $voyage->name }}</span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-calendar-alt payment-icon"></i>Отправление:
                                </span>
                                <span class="voyage-info-value">
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <br><small style="color: #94a3b8;">{{ $voyage->departurePlace->name }}</small>
                                    @endif
                                </span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-map-marker-alt payment-icon"></i>Прибытие:
                                </span>
                                <span class="voyage-info-value">
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <br><small style="color: #94a3b8;">{{ $voyage->arrivalPlace->name }}</small>
                                    @endif
                                </span>
                            </div>

                            <div class="voyage-info-item">
                                <span class="voyage-info-label">
                                    <i class="fas fa-clock payment-icon"></i>Время в пути:
                                </span>
                                <span class="voyage-info-value">{{ $voyage->travel_time }} часов</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Правая колонка: Детали заказа --}}
                <div class="col-lg-7">
                    <div class="payment-card">
                        <div class="payment-card-header">
                            <h3 class="payment-card-title">
                                <i class="fas fa-list-ul me-2"></i>Детали заказа
                            </h3>
                        </div>
                        <div class="payment-card-body">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Наименование</th>
                                        <th style="text-align: center; width: 100px;">Кол-во</th>
                                        <th style="text-align: right; width: 150px;">Цена</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Билеты --}}
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>
                                                <div class="item-name">
                                                    <i class="fas fa-ticket-alt me-2"></i>
                                                    {{ $ticket->type ?? 'Билет' }}
                                                </div>
                                                <small class="item-type">
                                                    Место: {{ $ticket->place_number ?? 'Стандарт' }}
                                                </small>
                                            </td>
                                            <td class="item-quantity">1</td>
                                            <td class="item-price">{{ number_format($ticket->price, 0) }} ₽</td>
                                        </tr>
                                    @endforeach

                                    {{-- Развлечения --}}
                                    @if(count($entertainmentItems) > 0)
                                        @foreach($entertainmentItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="item-name">
                                                        <i class="fas fa-star me-2"></i>
                                                        {{ $item['entertainment']->name }}
                                                    </div>
                                                    <small class="item-type">
                                                        {{ $item['entertainment']->description ?? 'Дополнительная услуга' }}
                                                    </small>
                                                </td>
                                                <td class="item-quantity">{{ $item['quantity'] }}</td>
                                                <td class="item-price">{{ number_format($item['subtotal'], 0) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            {{-- Итоговая сумма --}}
                            <div class="total-section">
                                <div class="total-row">
                                    <div class="total-label">
                                        <i class="fas fa-calculator me-2"></i>Итого к оплате:
                                    </div>
                                    <div class="total-amount">
                                        {{ number_format($totalPrice, 0) }} ₽
                                    </div>
                                </div>

                                {{-- Форма оплаты --}}
                                <form action="{{ route('shop.process-payment') }}" method="POST" id="payment-form">
                                    @csrf

                                    <div class="payment-actions">
                                        <button type="submit" class="btn-pay" id="pay-btn">
                                            <i class="fas fa-lock me-2"></i>Оплатить {{ number_format($totalPrice, 0) }} ₽
                                        </button>
                                        <a href="{{ route('shop') }}" class="btn-cancel">
                                            <i class="fas fa-times me-2"></i>Отмена
                                        </a>
                                    </div>
                                </form>
                            </div>

                            {{-- Информация о безопасности --}}
                            <div class="mt-4 text-center">
                                <small style="color: #94a3b8;">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Безопасная оплата через защищенное соединение
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const payBtn = document.getElementById('pay-btn');

    form.addEventListener('submit', function(e) {
        // Показываем индикатор загрузки
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Обработка платежа...';
        payBtn.disabled = true;
    });
});
</script>
@endsection
