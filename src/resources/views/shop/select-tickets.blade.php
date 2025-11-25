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
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show select-tickets-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Ошибки валидации:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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

                {{-- БИЛЕТЫ - СХЕМА КОРАБЛЯ - НА ВСЮ ШИРИНУ --}}
                <div class="row g-4">
                    <div class="col-12">
                        @if($tickets->count() > 0)
                            <div class="ship-layout">

                                @php
                                    // Группируем билеты по типу
                                    $ticketsByType = $tickets->groupBy('type');
                                @endphp


                                {{-- Переключатель этажей --}}
                                <div class="deck-selector">

                                    @forelse($availableCabinTypes as $cabinType)
                                        @php
                                            $deckKey = match(trim($cabinType->name)) {
                                                'Первый класс' => 'first-class',
                                                'Второй класс', 'Бизнес класс', 'Бизнес-класс' => 'business-class',
                                                'Третий класс', 'Эконом класс', 'Эконом-класс' => 'economy-class',
                                                default => 'economy-class'
                                            };

                                            $icon = match(trim($cabinType->name)) {
                                                'Первый класс' => 'fa-crown',
                                                'Второй класс', 'Бизнес класс', 'Бизнес-класс' => 'fa-gem',
                                                default => 'fa-ship'
                                            };

                                            $deckName = match(trim($cabinType->name)) {
                                                'Первый класс' => 'Верхняя палуба',
                                                'Второй класс', 'Бизнес класс', 'Бизнес-класс' => 'Средняя палуба',
                                                default => 'Нижняя палуба'
                                            };
                                        @endphp

                                        <button type="button"
                                                class="deck-button {{ $loop->first ? 'active' : '' }}"
                                                data-deck="{{ $deckKey }}">
                                            <i class="fas {{ $icon }}"></i>
                                            <span>{{ $cabinType->name }}</span>
                                            <small>{{ $deckName }}</small>
                                        </button>

                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-muted"></i>
                                            <p class="text-muted">Нет доступных мест на этот рейс</p>
                                        </div>
                                    @endforelse

                                </div>

                                {{-- Контейнер палуб --}}
                                <div class="decks-container">
                                    @forelse($availableCabinTypes as $cabinType)
                                        @php
                                            $deckKey = match(trim($cabinType->name)) {
                                                'Первый класс' => 'first-class',
                                                'Второй класс', 'Бизнес класс', 'Бизнес-класс' => 'business-class',
                                                default => 'economy-class'
                                            };

                                            $gridClass = match($deckKey) {
                                                'first-class' => 'first-class',
                                                'business-class' => 'business-class',
                                                default => 'economy-class'
                                            };
                                        @endphp

                                        <div class="deck-section {{ $deckKey }}"
                                            data-deck="{{ $deckKey }}"
                                            style="display: {{ $loop->first ? 'block' : 'none' }};">

                                            <div class="deck-header">
                                                <i class="fas {{ $loop->first ? 'fa-crown' : ($deckKey === 'business-class' ? 'fa-gem' : 'fa-ship') }} me-2"></i>
                                                {{ $cabinType->name }} — {{ $cabinType->description }}
                                            </div>

                                            <div class="deck-body">
                                                <div class="deck-image-wrapper">
                                                    <img src="/images/decks/{{ $deckKey }}-deck.png?v={{ time() }}"
                                                         alt="{{ $cabinType->name }}"
                                                         class="deck-image">
                                                    <div class="seats-container {{ $gridClass }}" data-deck-type="{{ $deckKey }}">
                                                        @foreach($cabinType->tickets as $ticket)
                                                        @php
                                                            $isBooked = $ticket->status === 'Забронировано';
                                                            $seatClass = $isBooked ? 'seat booked' : 'seat available';
                                                            // Добавляем класс размера в зависимости от типа палубы
                                                            $seatClass .= ' ' . $deckKey . '-seat';
                                                            $tooltipText = $isBooked
                                                                ? "Место {$ticket->number} — Забронировано"
                                                                : number_format($ticket->price, 0, '', ' ') . " ₽";
                                                            // Порядковый индекс места в рамках этого класса каюты (начиная с 0)
                                                            $seatIndex = $loop->index;
                                                        @endphp
                                                        <div class="{{ $seatClass }}"
                                                            data-ticket-id="{{ $ticket->id }}"
                                                            data-price="{{ $ticket->price }}"
                                                            data-place="{{ $ticket->number }}"
                                                            data-seat-index="{{ $seatIndex }}"
                                                            title="{{ $tooltipText }}">
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- Нет доступных кают -->
                                    @endforelse
                                </div>

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
                                        <span>Забронировано</span>
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
                                <i class="fas fa-info-circle me-1"></i>Нажмите на квадрат, чтобы выбрать место
                            </div>
                        @else
                            <p class="empty-message text-center py-5">
                                <i class="fas fa-sad-tear fa-3x mb-3 d-block"></i>
                                К сожалению, билеты на этот рейс закончились.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- РАЗВЛЕЧЕНИЯ И ИТОГО - ДВЕ КОЛОНКИ --}}
                <div class="row g-4 mt-4">
                    {{-- РАЗВЛЕЧЕНИЯ --}}
                    <div class="col-lg-8">
                        <div class="section-card entertainment-card-select">
                            <div class="section-header p-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-umbrella-beach me-2"></i>Дополнительные развлечения
                                </h5>
                            </div>
                            <div class="card-body p-2">
                                @if($entertainments->count() > 0)
                                    <div class="accordion" id="entertainmentsAccordionSelect">
                                        @foreach($entertainments->chunk(ceil($entertainments->count() / 3)) as $index => $entertainmentChunk)
                                            <div class="accordion-item entertainment-accordion-item">
                                                <h2 class="accordion-header" id="headingSelect{{ $index }}">
                                                    <button class="accordion-button collapsed entertainment-accordion-button"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseSelect{{ $index }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapseSelect{{ $index }}">
                                                        Группа развлечений {{ $index + 1 }}
                                                        <i class="fas fa-chevron-down ms-2"></i>
                                                    </button>
                                                </h2>
                                                <div id="collapseSelect{{ $index }}"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="headingSelect{{ $index }}"
                                                     data-bs-parent="#entertainmentsAccordionSelect">
                                                    <div class="accordion-body p-2">
                                                        @foreach($entertainmentChunk as $ent)
                                                            <div class="entertainment-item mb-3">
                                                                <div class="entertainment-info d-flex justify-content-between align-items-start mb-2">
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
                                                                    <input type="hidden" name="entertainments[{{ $loop->parent->index * ceil($entertainments->count() / 3) + $loop->index }}][id]" value="{{ $ent->id }}">
                                                                    <input type="number"
                                                                           name="entertainments[{{ $loop->parent->index * ceil($entertainments->count() / 3) + $loop->index }}][quantity]"
                                                                           value="0"
                                                                           min="0"
                                                                           max="10"
                                                                           class="quantity-input form-control"
                                                                           data-price="{{ $ent->price }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
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
                    </div>

                    {{-- ИТОГО --}}
                    <div class="col-lg-4">
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

    // Переключение этажей
    const deckButtons = document.querySelectorAll('.deck-button');
    const deckSections = document.querySelectorAll('.deck-section[data-deck]');

    deckButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetDeck = this.dataset.deck;

            // Убираем активный класс со всех кнопок
            deckButtons.forEach(btn => btn.classList.remove('active'));

            // Добавляем активный класс к нажатой кнопке
            this.classList.add('active');

            // Скрываем все палубы и показываем выбранную
            deckSections.forEach(section => {
                if (section.dataset.deck === targetDeck) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });

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

        seat.addEventListener('click', function(e) {
            const ticketId = this.dataset.ticketId;
            const checkbox = document.getElementById('ticket-' + ticketId);

            console.log('Клик по билету:', ticketId, 'Трапеция:', this.classList.contains('trapezoid-left') || this.classList.contains('trapezoid-right'));

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
            } else {
                console.error('Чекбокс не найден для билета:', ticketId);
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

{{-- Подключение конфигурации позиций мест --}}
<script src="{{ asset('js/seat-positions.js') }}"></script>
<script>
// Применяем позиции к местам после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    // Для каждой палубы применяем позиции
    const deckContainers = document.querySelectorAll('.seats-container[data-deck-type]');

    deckContainers.forEach(container => {
        const deckType = container.dataset.deckType;
        const seats = container.querySelectorAll('.seat[data-seat-index]');

        // Получаем позиции для этого типа палубы
        const positions = seatPositions[deckType];

        if (!positions) {
            console.warn('Позиции не найдены для палубы:', deckType);
            return;
        }

        seats.forEach(seat => {
            const seatIndex = parseInt(seat.dataset.seatIndex);
            const position = positions[seatIndex];

            if (position) {
                // Применяем позицию
                seat.style.position = 'absolute';
                seat.style.top = position.top + '%';
                seat.style.left = position.left + '%';
                seat.style.transform = 'translate(-50%, -50%)'; // Центрируем относительно координат

                // Если место должно быть трапецией, добавляем специальный класс
                if (position.isTrapezoid) {
                    if (position.isTrapezoid === 'left') {
                        seat.classList.add('trapezoid-left');
                    } else if (position.isTrapezoid === 'right') {
                        seat.classList.add('trapezoid-right');
                    }
                }
            } else {
                console.warn('Позиция не найдена для места', seatIndex, 'на палубе', deckType);
            }
        });
    });
});
</script>
@endsection
