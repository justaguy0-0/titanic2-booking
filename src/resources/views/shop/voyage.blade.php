@extends('head')

@section('title', 'Выбор билетов — ' . $voyage->name)

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Сообщения --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Информация о рейсе --}}
            <div class="voyage-info-card mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="voyage-title mb-3">{{ $voyage->name }}</h2>
                            <div class="voyage-details">
                                <div class="detail-item">
                                    <strong>Отправление:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <span class="place-name">{{ $voyage->departurePlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <strong>Прибытие:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <span class="place-name">{{ $voyage->arrivalPlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <strong>Время в пути:</strong> {{ $voyage->travel_time }} ч
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('shop') }}" class="btn-back">
                                ← Назад к рейсам
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Форма покупки --}}
            <form action="{{ route('shop.purchase') }}" method="POST" id="purchase-form">
                @csrf
                <input type="hidden" name="voyage_id" value="{{ $voyage->id }}">

                <div class="row g-4">

                    {{-- БИЛЕТЫ --}}
                    <div class="col-lg-7">
                        <div class="section-card">
                            <div class="section-header">
                                <h5 class="mb-0">Выберите билеты</h5>
                            </div>
                            <div class="card-body">
                                @if($tickets->count() > 0)
                                    <div class="tickets-grid">
                                        @foreach($tickets as $ticket)
                                            <div class="ticket-item">
                                                <div class="form-check">
                                                    <input class="form-check-input ticket-checkbox" 
                                                           type="checkbox" 
                                                           name="tickets[]" 
                                                           value="{{ $ticket->id }}" 
                                                           id="ticket-{{ $ticket->id }}"
                                                           data-price="{{ $ticket->price }}">
                                                    <label class="form-check-label ticket-label" for="ticket-{{ $ticket->id }}">
                                                        {{ $ticket->type ?? 'Стандартный билет' }}
                                                        @if($ticket->place_number)
                                                            <span class="place-number">(Место {{ $ticket->place_number }})</span>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="ticket-price">
                                                    {{ number_format($ticket->price, 0) }} ₽
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="selection-note">
                                        * Обязательно выберите хотя бы один билет
                                    </div>
                                @else
                                    <p class="empty-message">
                                        К сожалению, билеты на этот рейс закончились.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- РАЗВЛЕЧЕНИЯ И ИТОГО --}}
                    <div class="col-lg-5">
                        {{-- РАЗВЛЕЧЕНИЯ --}}
                        <div class="section-card">
                            <div class="section-header">
                                <h5 class="mb-0">Дополнительные развлечения</h5>
                            </div>
                            <div class="card-body">
                                @if($entertainments->count() > 0)
                                    <div class="entertainments-list">
                                        @foreach($entertainments as $ent)
                                            <div class="entertainment-item">
                                                <div class="entertainment-info">
                                                    <div class="flex-grow-1">
                                                        <h6 class="entertainment-name">{{ $ent->name }}</h6>
                                                        <small class="entertainment-desc">{{ $ent->description ?? 'Дополнительная услуга' }}</small>
                                                    </div>
                                                    <div class="entertainment-price">
                                                        {{ number_format($ent->price, 0) }} ₽
                                                    </div>
                                                </div>
                                                <div class="quantity-control">
                                                    <label class="quantity-label">Количество:</label>
                                                    <input type="hidden" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}">
                                                    <input type="number" 
                                                           name="entertainments[{{ $loop->index }}][quantity]" 
                                                           value="0" 
                                                           min="0" 
                                                           max="10"
                                                           class="quantity-input" 
                                                           data-price="{{ $ent->price }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="empty-message">
                                        Развлечения скоро появятся!
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- ИТОГО --}}
                        <div class="total-card">
                            <div class="card-body p-4">
                                <div class="total-row">
                                    <h5 class="total-label">Итого:</h5>
                                    <h4 class="total-amount" id="total-price">0 ₽</h4>
                                </div>
                                <button type="submit" 
                                        class="btn-submit" 
                                        id="submit-btn" 
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalPriceEl = document.getElementById('total-price');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('purchase-form');

    function updateTotal() {
        let total = 0;
        let hasTickets = false;

        ticketCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                hasTickets = true;
                total += parseFloat(checkbox.dataset.price);
            }
        });

        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price);
            total += quantity * price;
        });

        totalPriceEl.textContent = total.toLocaleString('ru-RU') + ' ₽';
        submitBtn.disabled = !hasTickets;
    }

    form.addEventListener('submit', function(e) {
        const hasChecked = document.querySelector('.ticket-checkbox:checked');
        if (!hasChecked) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы один билет!');
            return false;
        }
    });

    ticketCheckboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    quantityInputs.forEach(input => input.addEventListener('input', updateTotal));

    updateTotal();
});
</script>

@endsection