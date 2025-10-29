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
            <div class="card border-0 shadow-sm mb-4" style="background: #1e293b; border: 1px solid #334155;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h4 fw-bold mb-3" style="color: #fbbf24;">{{ $voyage->name }}</h2>
                            <div class="d-flex flex-wrap gap-3 small" style="color: #fcd34d;">
                                <div>
                                    <strong>Отправление:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    @if($voyage->departurePlace)
                                        <span class="ms-1" style="color: #fbbf24;">{{ $voyage->departurePlace->name }}</span>
                                    @endif
                                </div>
                                <div>
                                    <strong>Прибытие:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    @if($voyage->arrivalPlace)
                                        <span class="ms-1" style="color: #fbbf24;">{{ $voyage->arrivalPlace->name }}</span>
                                    @endif
                                </div>
                                <div>
                                    <strong>Время в пути:</strong> {{ $voyage->travel_time }} ч
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('shop') }}" class="btn btn-outline-warning">
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
                        <div class="card border-0 shadow-sm" style="background: #1e293b; border: 1px solid #334155;">
                            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                                <h5 class="mb-0" style="color: #fbbf24;">Выберите билеты</h5>
                            </div>
                            <div class="card-body">
                                @if($tickets->count() > 0)
                                    <div class="row row-cols-1 g-3">
                                        @foreach($tickets as $ticket)
                                            <div class="col">
                                                <div class="border rounded p-3 d-flex justify-content-between align-items-center hover-shadow transition" 
                                                     style="background: #0f172a; border-color: #334155 !important;">
                                                    <div class="form-check">
                                                        <input class="form-check-input ticket-checkbox" 
                                                               type="checkbox" 
                                                               name="tickets[]" 
                                                               value="{{ $ticket->id }}" 
                                                               id="ticket-{{ $ticket->id }}"
                                                               data-price="{{ $ticket->price }}">
                                                        <label class="form-check-label fw-medium" for="ticket-{{ $ticket->id }}" style="color: #fcd34d;">
                                                            {{ $ticket->type ?? 'Стандартный билет' }}
                                                            @if($ticket->place_number)
                                                                <span class="text-muted small">(Место {{ $ticket->place_number }})</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="h5 fw-bold mb-0" style="color: #fbbf24;">
                                                            {{ number_format($ticket->price, 0) }} ₽
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 text-danger small">
                                        * Обязательно выберите хотя бы один билет
                                    </div>
                                @else
                                    <p class="text-center py-4" style="color: #94a3b8;">
                                        К сожалению, билеты на этот рейс закончились.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- РАЗВЛЕЧЕНИЯ И ИТОГО --}}
                    <div class="col-lg-5">
                        {{-- РАЗВЛЕЧЕНИЯ --}}
                        <div class="card border-0 shadow-sm" style="background: #1e293b; border: 1px solid #334155;">
                            <div class="card-header py-3" style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                                <h5 class="mb-0" style="color: #fbbf24;">Дополнительные развлечения</h5>
                            </div>
                            <div class="card-body">
                                @if($entertainments->count() > 0)
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($entertainments as $ent)
                                            <div class="border rounded p-3" style="background: #0f172a; border-color: #334155 !important;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="fw-bold mb-1" style="color: #fcd34d;">{{ $ent->name }}</h6>
                                                        <small class="text-muted">{{ $ent->description ?? 'Дополнительная услуга' }}</small>
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        <span class="h6 fw-bold mb-0" style="color: #fbbf24;">
                                                            {{ number_format($ent->price, 0) }} ₽
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <label class="small text-muted me-2" style="color: #94a3b8;">Количество:</label>
                                                    <input type="hidden" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}">
                                                    <input type="number" 
                                                           name="entertainments[{{ $loop->index }}][quantity]" 
                                                           value="0" 
                                                           min="0" 
                                                           max="10"
                                                           class="form-control form-control-sm quantity-input" 
                                                           data-price="{{ $ent->price }}"
                                                           style="width: 80px; background: #0f172a; border-color: #334155; color: #fcd34d;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center py-3" style="color: #94a3b8;">
                                        Развлечения скоро появятся!
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- ИТОГО --}}
                        <div class="card border-0 shadow-sm mt-4" style="background: #1e293b; border: 1px solid #334155;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0" style="color: #fbbf24;">Итого:</h5>
                                    <h4 class="fw-bold mb-0" style="color: #fbbf24;" id="total-price">0 ₽</h4>
                                </div>
                                <button type="submit" 
                                        class="btn btn-gold w-100 fw-bold py-2" 
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

        // Считаем стоимость билетов
        ticketCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                hasTickets = true;
                const price = parseFloat(checkbox.dataset.price);
                total += price;
            }
        });

        // Считаем стоимость развлечений
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price);
            total += quantity * price;
        });

        // Обновляем итоговую сумму
        totalPriceEl.textContent = total.toLocaleString('ru-RU') + ' ₽';
        
        // Активируем/деактивируем кнопку
        submitBtn.disabled = !hasTickets;
    }

    // Валидация перед отправкой формы
    form.addEventListener('submit', function(e) {
        const hasChecked = document.querySelector('.ticket-checkbox:checked');
        if (!hasChecked) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы один билет!');
            return false;
        }
    });

    // Добавляем обработчики событий
    ticketCheckboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    quantityInputs.forEach(input => input.addEventListener('input', updateTotal));

    // Инициализация
    updateTotal();
});
</script>

<style>
body {
    background: #0f172a;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25) !important;
    transform: translateY(-2px);
    border-color: #fbbf24 !important;
}

.btn-gold {
    background: linear-gradient(45deg, #fbbf24, #f59e0b);
    border: none;
    color: #1e293b;
    font-weight: bold;
    transition: 0.3s;
}

.btn-gold:hover {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: #1e293b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
}

.btn-gold:disabled {
    background: #334155;
    color: #64748b;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-outline-warning {
    border-color: #fbbf24;
    color: #fbbf24;
}

.btn-outline-warning:hover {
    background: #fbbf24;
    color: #1e293b;
}

.form-check-input:checked {
    background-color: #fbbf24;
    border-color: #fbbf24;
}

.form-control:focus {
    border-color: #fbbf24;
    box-shadow: 0 0 0 0.2rem rgba(251, 191, 36, 0.25);
}
</style>
@endsection