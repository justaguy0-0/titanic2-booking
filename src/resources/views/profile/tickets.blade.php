@extends('head')

@section('title', 'Оформление заказа')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Оформление заказа</h2>
                <a href="{{ route('checkout.index') }}" class="btn-back">← Назад к рейсам</a>
            </div>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Информация о рейсе --}}
            <div class="voyage-card mb-4">
                <div class="card-body p-4">
                    <h3 class="voyage-name mb-3">{{ $voyage->name }}</h3>
                    <div class="voyage-info-grid">
                        <div class="info-item">
                            <span class="info-label">Маршрут</span>
                            <span class="info-value">{{ $voyage->placeDeparture->name }} → {{ $voyage->icebergArrival->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Отправление</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Время в пути</span>
                            <span class="info-value">{{ $voyage->travel_time }} часов</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="voyage_id" value="{{ $voyage->id }}">

                <div class="row g-4">
                    {{-- Билеты --}}
                    <div class="col-lg-7">
                        <div class="section-card">
                            <div class="section-header">
                                <h5 class="mb-0">Выберите билеты</h5>
                            </div>
                            <div class="card-body">
                                @if($tickets->count() > 0)
                                    <div class="tickets-grid">
                                        @foreach($tickets as $ticket)
                                            <label class="ticket-item-checkbox">
                                                <input type="checkbox" 
                                                       name="tickets[]" 
                                                       value="{{ $ticket->id }}" 
                                                       class="ticket-checkbox" 
                                                       data-price="{{ $ticket->price }}"
                                                       onchange="updateTotal()">
                                                <div class="ticket-info">
                                                    <div>
                                                        <span class="ticket-type">{{ $ticket->type }}</span>
                                                        <span class="ticket-number">№ {{ $ticket->number }}</span>
                                                    </div>
                                                    <span class="ticket-price">{{ number_format($ticket->price, 0) }} ₽</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="selection-note">Выберите хотя бы один билет</p>
                                @else
                                    <p class="empty-message">Нет доступных билетов на этот рейс</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Развлечения и итого --}}
                    <div class="col-lg-5">
                        @if($entertainments->count() > 0)
                            <div class="section-card mb-4">
                                <div class="section-header">
                                    <h5 class="mb-0">Дополнительные развлечения</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($entertainments as $entertainment)
                                        <div class="entertainment-item">
                                            <div class="entertainment-info-text">
                                                <h6 class="entertainment-name">{{ $entertainment->name }}</h6>
                                                <p class="entertainment-price-small">{{ number_format($entertainment->price, 0) }} ₽</p>
                                            </div>
                                            <div class="quantity-controls">
                                                <button type="button" class="qty-btn" onclick="decrementEntertainment({{ $entertainment->id }})">−</button>
                                                <input type="number" 
                                                       id="entertainment_{{ $entertainment->id }}_qty"
                                                       class="qty-input entertainment-quantity"
                                                       value="0" 
                                                       min="0"
                                                       data-id="{{ $entertainment->id }}"
                                                       data-price="{{ $entertainment->price }}"
                                                       onchange="updateTotal()"
                                                       readonly>
                                                <button type="button" class="qty-btn" onclick="incrementEntertainment({{ $entertainment->id }})">+</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Итого --}}
                        <div class="total-card">
                            <div class="card-body p-4">
                                <div class="total-row">
                                    <h5 class="total-label">Итого:</h5>
                                    <h4 class="total-amount" id="totalPrice">0 ₽</h4>
                                </div>
                                <button type="submit" 
                                        class="btn-submit w-100" 
                                        id="submitButton"
                                        disabled>
                                    Оформить заказ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    let total = 0;
    let hasTickets = false;

    document.querySelectorAll('.ticket-checkbox:checked').forEach(checkbox => {
        total += parseFloat(checkbox.dataset.price);
        hasTickets = true;
    });

    document.querySelectorAll('.entertainment-quantity').forEach(input => {
        const quantity = parseInt(input.value) || 0;
        const price = parseFloat(input.dataset.price);
        total += quantity * price;
    });

    document.getElementById('totalPrice').textContent = 
        new Intl.NumberFormat('ru-RU').format(total) + ' ₽';

    document.getElementById('submitButton').disabled = !hasTickets;
}

function incrementEntertainment(id) {
    const input = document.getElementById(`entertainment_${id}_qty`);
    input.value = parseInt(input.value) + 1;
    updateTotal();
}

function decrementEntertainment(id) {
    const input = document.getElementById(`entertainment_${id}_qty`);
    if (parseInt(input.value) > 0) {
        input.value = parseInt(input.value) - 1;
        updateTotal();
    }
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    document.querySelectorAll('input[name^="entertainments"]').forEach(el => el.remove());

    document.querySelectorAll('.entertainment-quantity').forEach((input, index) => {
        const quantity = parseInt(input.value);
        if (quantity > 0) {
            const id = input.dataset.id;
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `entertainments[${index}][id]`;
            idInput.value = id;
            this.appendChild(idInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = `entertainments[${index}][quantity]`;
            qtyInput.value = quantity;
            this.appendChild(qtyInput);
        }
    });
});
</script>
@endsection