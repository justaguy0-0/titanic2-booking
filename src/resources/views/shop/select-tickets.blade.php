@extends('head')

@section('title', 'Выбор билетов — ' . $voyage->name)

@section('main_content')
<div class="container py-5 select-tickets-container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Сообщения --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show select-tickets-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show select-tickets-alert" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif

            {{-- Информация о рейсе --}}
            <div class="voyage-info-card mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="voyage-title mb-3">
                                <i class="fas fa-ship me-2"></i>{{ $voyage->name }}
                            </h2>
                            <div class="voyage-details">
                                <div class="detail-item">
                                    <strong><i class="fas fa-anchor me-2"></i>Отправление:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <span class="place-name ms-2">{{ $voyage->departurePlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Прибытие:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <span class="place-name ms-2">{{ $voyage->arrivalPlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <strong><i class="fas fa-clock me-2"></i>Время в пути:</strong> {{ $voyage->travel_time }} ч
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('shop') }}" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Назад к рейсам
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

                    {{-- БИЛЕТЫ - СХЕМА КОРАБЛЯ --}}
                    <div class="col-lg-7">
                        <div class="section-card">
                            <div class="section-header p-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-ship me-2"></i>Выберите место на корабле
                                </h5>
                            </div>
                            <div class="card-body p-3">
                                @if($tickets->count() > 0)
                                    <div class="ship-layout">
                                        <h3 class="ship-title">
                                            <i class="fas fa-anchor me-2"></i>ТИТАНИК 2
                                        </h3>

                                        @php
                                            // Группируем билеты по типу
                                            $ticketsByType = $tickets->groupBy('type');
                                        @endphp

                                        {{-- Первый класс --}}
                                        @if($ticketsByType->has('Первый класс'))
                                            <div class="deck-section">
                                                <div class="deck-header">
                                                    <i class="fas fa-crown me-2"></i>Первый Класс - Люкс Палуба
                                                </div>
                                                <div class="deck-body">
                                                    <div class="seats-grid first-class">
                                                        @foreach($ticketsByType->get('Первый класс') as $ticket)
                                                            <div class="seat"
                                                                 data-ticket-id="{{ $ticket->id }}"
                                                                 data-price="{{ $ticket->price }}"
                                                                 data-type="first">
                                                                <div class="seat-icon">👑</div>
                                                                <div class="seat-number">{{ $ticket->place_number ?? 'A'.$loop->iteration }}</div>
                                                                <div class="seat-price">{{ number_format($ticket->price, 0) }}₽</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Бизнес класс --}}
                                        @if($ticketsByType->has('Бизнес класс'))
                                            <div class="deck-section">
                                                <div class="deck-header">
                                                    <i class="fas fa-gem me-2"></i>Бизнес Класс - Средняя Палуба
                                                </div>
                                                <div class="deck-body">
                                                    <div class="seats-grid business-class">
                                                        @foreach($ticketsByType->get('Бизнес класс') as $ticket)
                                                            <div class="seat"
                                                                 data-ticket-id="{{ $ticket->id }}"
                                                                 data-price="{{ $ticket->price }}"
                                                                 data-type="business">
                                                                <div class="seat-icon">💎</div>
                                                                <div class="seat-number">{{ $ticket->place_number ?? 'B'.$loop->iteration }}</div>
                                                                <div class="seat-price">{{ number_format($ticket->price, 0) }}₽</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Эконом класс --}}
                                        @if($ticketsByType->has('Эконом класс'))
                                            <div class="deck-section">
                                                <div class="deck-header">
                                                    <i class="fas fa-ship me-2"></i>Эконом Класс - Нижняя Палуба
                                                </div>
                                                <div class="deck-body">
                                                    <div class="seats-grid economy-class">
                                                        @foreach($ticketsByType->get('Эконом класс') as $ticket)
                                                            <div class="seat"
                                                                 data-ticket-id="{{ $ticket->id }}"
                                                                 data-price="{{ $ticket->price }}"
                                                                 data-type="economy">
                                                                <div class="seat-icon">🛏️</div>
                                                                <div class="seat-number">{{ $ticket->place_number ?? 'C'.$loop->iteration }}</div>
                                                                <div class="seat-price">{{ number_format($ticket->price, 0) }}₽</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Если есть билеты без типа --}}
                                        @if($ticketsByType->has(null) || $ticketsByType->has(''))
                                            <div class="deck-section">
                                                <div class="deck-header">
                                                    <i class="fas fa-chair me-2"></i>Стандартные Места
                                                </div>
                                                <div class="deck-body">
                                                    <div class="seats-grid business-class">
                                                        @foreach($tickets->whereNull('type')->merge($tickets->where('type', '')) as $ticket)
                                                            <div class="seat"
                                                                 data-ticket-id="{{ $ticket->id }}"
                                                                 data-price="{{ $ticket->price }}"
                                                                 data-type="standard">
                                                                <div class="seat-number">{{ $ticket->place_number ?? 'S'.$loop->iteration }}</div>
                                                                <div class="seat-price">{{ number_format($ticket->price, 0) }}₽</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Легенда --}}
                                        <div class="ship-legend">
                                            <div class="legend-item">
                                                <div class="legend-box available"></div>
                                                <span>Доступно</span>
                                            </div>
                                            <div class="legend-item">
                                                <div class="legend-box selected"></div>
                                                <span>Выбрано</span>
                                            </div>
                                            <div class="legend-item">
                                                <div class="legend-box booked"></div>
                                                <span>Занято</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Скрытые чекбоксы для формы --}}
                                    @foreach($tickets as $ticket)
                                        <input type="checkbox"
                                               class="d-none ticket-checkbox"
                                               name="tickets[]"
                                               value="{{ $ticket->id }}"
                                               id="ticket-{{ $ticket->id }}"
                                               data-price="{{ $ticket->price }}">
                                    @endforeach

                                    <div class="selection-note mt-3 text-center">
                                        <i class="fas fa-info-circle me-1"></i>Нажмите на место, чтобы выбрать его
                                    </div>
                                @else
                                    <p class="empty-message text-center py-5">
                                        <i class="fas fa-sad-tear fa-3x mb-3 d-block"></i>
                                        К сожалению, билеты на этот рейс закончились.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- РАЗВЛЕЧЕНИЯ И ИТОГО --}}
                    <div class="col-lg-5">
                        {{-- РАЗВЛЕЧЕНИЯ --}}
                        <div class="section-card mb-4">
                            <div class="section-header p-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-umbrella-beach me-2"></i>Дополнительные развлечения
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                @if($entertainments->count() > 0)
                                    <div class="entertainments-list">
                                        @foreach($entertainments as $ent)
                                            <div class="entertainment-item">
                                                <div class="entertainment-info d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <h6 class="entertainment-name mb-1">
                                                            <i class="fas fa-star me-2"></i>{{ $ent->name }}
                                                        </h6>
                                                        <small class="entertainment-desc">
                                                            {{ $ent->description ?? 'Дополнительная услуга' }}
                                                        </small>
                                                    </div>
                                                    <div class="entertainment-price ms-3">
                                                        {{ number_format($ent->price, 0) }} ₽
                                                    </div>
                                                </div>
                                                <div class="quantity-control d-flex align-items-center">
                                                    <label class="quantity-label me-3">
                                                        Количество:
                                                    </label>
                                                    <input type="hidden" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}">
                                                    <input type="number"
                                                           name="entertainments[{{ $loop->index }}][quantity]"
                                                           value="0"
                                                           min="0"
                                                           max="10"
                                                           class="quantity-input form-control"
                                                           data-price="{{ $ent->price }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="empty-message text-center py-4">
                                        <i class="fas fa-hourglass-half fa-2x mb-3 d-block"></i>
                                        Развлечения скоро появятся!
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- ИТОГО --}}
                        <div class="total-card">
                            <div class="card-body p-4">
                                <div class="total-row d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="total-label mb-0">
                                        <i class="fas fa-calculator me-2"></i>Итого:
                                    </h5>
                                    <h4 class="total-amount mb-0" id="total-price">0 ₽</h4>
                                </div>
                                <button type="submit"
                                        class="btn-submit w-100 fw-bold"
                                        id="submit-btn"
                                        disabled>
                                    <i class="fas fa-shopping-cart me-2"></i>Оформить заказ
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
document.addEventListener('DOMContentLoaded', function() {
    const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalPriceEl = document.getElementById('total-price');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('purchase-form');
    const seats = document.querySelectorAll('.seat');

    function updateTotal() {
        let total = 0;
        let hasTickets = false;

        // Считаем стоимость билетов
        ticketCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                hasTickets = true;
                const price = parseFloat(checkbox.dataset.price) || 0;
                total += price;
            }
        });

        // Считаем стоимость развлечений
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            total += quantity * price;
        });

        // Обновляем отображение
        totalPriceEl.textContent = total.toLocaleString('ru-RU') + ' ₽';

        // Обновляем состояние кнопки
        if (hasTickets) {
            submitBtn.disabled = false;
            submitBtn.classList.add('active');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.remove('active');
        }
    }

    // Обработка кликов по местам на корабле
    seats.forEach(seat => {
        // Пропускаем забронированные места
        if (seat.classList.contains('booked')) {
            return;
        }

        seat.addEventListener('click', function() {
            const ticketId = this.dataset.ticketId;
            const checkbox = document.getElementById('ticket-' + ticketId);

            if (checkbox) {
                // Переключаем состояние чекбокса
                checkbox.checked = !checkbox.checked;

                // Переключаем визуальное состояние места
                if (checkbox.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }

                // Обновляем итоговую сумму
                updateTotal();
            }
        });
    });

    // Валидация формы
    form.addEventListener('submit', function(e) {
        const hasChecked = document.querySelector('.ticket-checkbox:checked');
        if (!hasChecked) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы один билет!');
            return false;
        }

        // Показываем сообщение о загрузке
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Обработка...';
        submitBtn.disabled = true;
    });

    // Слушатели событий для чекбоксов
    ticketCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateTotal);
    });

    // Слушатели событий для количества развлечений
    quantityInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 10) this.value = 10;
            updateTotal();
        });
    });

    updateTotal();
});
</script>
@endsection
