@extends('head')

@section('title', 'Выбор билетов — ' . $voyage->name)

@section('main_content')
<div class="container py-5" style="background: #0f172a; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Сообщения --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #1e293b; border: 1px solid #fbbf24; color: #fcd34d;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #1e293b; border: 1px solid #fbbf24; color: #fcd34d;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                </div>
            @endif

            {{-- Информация о рейсе --}}
            <div class="voyage-info-card mb-4" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="voyage-title mb-3" style="color: #fbbf24;">{{ $voyage->name }}</h2>
                            <div class="voyage-details">
                                <div class="detail-item" style="color: #fcd34d; margin-bottom: 0.5rem;">
                                    <strong style="color: #fbbf24;">Отправление:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <span class="place-name" style="color: #fcd34d;">{{ $voyage->departurePlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item" style="color: #fcd34d; margin-bottom: 0.5rem;">
                                    <strong style="color: #fbbf24;">Прибытие:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <span class="place-name" style="color: #fcd34d;">{{ $voyage->arrivalPlace->name }}</span>
                                    @endif
                                </div>
                                <div class="detail-item" style="color: #fcd34d;">
                                    <strong style="color: #fbbf24;">Время в пути:</strong> {{ $voyage->travel_time }} ч
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('shop') }}" class="btn-back" style="color: #fbbf24; text-decoration: none; font-weight: 600;">
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
                        <div class="section-card" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155;">
                            <div class="section-header p-3" style="border-bottom: 1px solid #334155;">
                                <h5 class="mb-0" style="color: #fbbf24;">Выберите билеты</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($tickets->count() > 0)
                                    <div class="tickets-grid">
                                        @foreach($tickets as $ticket)
                                            <div class="ticket-item d-flex justify-content-between align-items-center py-3" style="border-bottom: 1px solid #334155;">
                                                <div class="form-check">
                                                    <input class="form-check-input ticket-checkbox" 
                                                           type="checkbox" 
                                                           name="tickets[]" 
                                                           value="{{ $ticket->id }}" 
                                                           id="ticket-{{ $ticket->id }}"
                                                           data-price="{{ $ticket->price }}"
                                                           style="background-color: #334155; border-color: #fbbf24;">
                                                    <label class="form-check-label ticket-label ms-2" for="ticket-{{ $ticket->id }}" style="color: #fcd34d;">
                                                        {{ $ticket->type ?? 'Стандартный билет' }}
                                                        @if($ticket->place_number)
                                                            <span class="place-number" style="color: #94a3b8;">(Место {{ $ticket->place_number }})</span>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="ticket-price fw-bold" style="color: #fbbf24;">
                                                    {{ number_format($ticket->price, 0) }} ₽
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="selection-note mt-3" style="color: #94a3b8; font-size: 0.875rem;">
                                        * Обязательно выберите хотя бы один билет
                                    </div>
                                @else
                                    <p class="empty-message text-center py-4" style="color: #94a3b8;">
                                        К сожалению, билеты на этот рейс закончились.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- РАЗВЛЕЧЕНИЯ И ИТОГО --}}
                    <div class="col-lg-5">
                        {{-- РАЗВЛЕЧЕНИЯ --}}
                        <div class="section-card mb-4" style="background: #1e293b; border-radius: 12px; border: 1px solid #334155;">
                            <div class="section-header p-3" style="border-bottom: 1px solid #334155;">
                                <h5 class="mb-0" style="color: #fbbf24;">Дополнительные развлечения</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($entertainments->count() > 0)
                                    <div class="entertainments-list">
                                        @foreach($entertainments as $ent)
                                            <div class="entertainment-item mb-4 pb-3" style="border-bottom: 1px solid #334155;">
                                                <div class="entertainment-info d-flex justify-content-between align-items-start mb-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="entertainment-name mb-1" style="color: #fcd34d;">{{ $ent->name }}</h6>
                                                        <small class="entertainment-desc" style="color: #94a3b8;">
                                                            {{ $ent->description ?? 'Дополнительная услуга' }}
                                                        </small>
                                                    </div>
                                                    <div class="entertainment-price fw-bold ms-3" style="color: #fbbf24; white-space: nowrap;">
                                                        {{ number_format($ent->price, 0) }} ₽
                                                    </div>
                                                </div>
                                                <div class="quantity-control d-flex align-items-center">
                                                    <label class="quantity-label me-2" style="color: #fcd34d; font-size: 0.875rem;">Количество:</label>
                                                    <input type="hidden" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}">
                                                    <input type="number" 
                                                           name="entertainments[{{ $loop->index }}][quantity]" 
                                                           value="0" 
                                                           min="0" 
                                                           max="10"
                                                           class="quantity-input form-control" 
                                                           data-price="{{ $ent->price }}"
                                                           style="background: #334155; border: 1px solid #475569; color: #fcd34d; width: 80px;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="empty-message text-center py-4" style="color: #94a3b8;">
                                        Развлечения скоро появятся!
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- ИТОГО --}}
                        <div class="total-card" style="background: #1e293b; border-radius: 12px; border: 2px solid #fbbf24;">
                            <div class="card-body p-4">
                                <div class="total-row d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="total-label mb-0" style="color: #fcd34d;">Итого:</h5>
                                    <h4 class="total-amount mb-0" id="total-price" style="color: #fbbf24;">0 ₽</h4>
                                </div>
                                <button type="submit" 
                                        class="btn-submit w-100 py-2 fw-bold" 
                                        id="submit-btn" 
                                        disabled
                                        style="background: #475569; border: none; color: #94a3b8; border-radius: 8px; transition: all 0.3s ease;">
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

<style>
    .form-check-input:checked {
        background-color: #fbbf24 !important;
        border-color: #fbbf24 !important;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(251, 191, 36, 0.25) !important;
        border-color: #fbbf24 !important;
    }
    
    .quantity-input:focus {
        border-color: #fbbf24 !important;
        box-shadow: 0 0 0 0.25rem rgba(251, 191, 36, 0.25) !important;
    }
    
    .btn-submit.active {
        background: linear-gradient(45deg, #fbbf24, #f59e0b) !important;
        color: #1e293b !important;
        cursor: pointer;
    }
    
    .btn-submit.active:hover {
        background: linear-gradient(45deg, #f59e0b, #d97706) !important;
        transform: translateY(-2px);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Скрипт загружен!');
    
    const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalPriceEl = document.getElementById('total-price');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('purchase-form');

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

    // Валидация формы
    form.addEventListener('submit', function(e) {
        const hasChecked = document.querySelector('.ticket-checkbox:checked');
        if (!hasChecked) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы один билет!');
            return false;
        }
        
        // Показываем сообщение о загрузке
        submitBtn.innerHTML = 'Обработка...';
        submitBtn.disabled = true;
    });

    // Слушатели событий
    ticketCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateTotal);
    });
    
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