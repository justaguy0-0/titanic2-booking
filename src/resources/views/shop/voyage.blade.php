@extends('head')

@section('title', 'Выбор билетов — ' . $voyage->name)

@section('main_content')
<div class="container">
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h4 fw-bold mb-2">{{ $voyage->name }}</h2>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <div>
                                    <strong>Отправление:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                    <span class="text-primary ms-1">{{ $voyage->placeDeparture->name }}</span>
                                </div>
                                <div>
                                    <strong>Прибытие:</strong>
                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                    <span class="text-primary ms-1">{{ $voyage->icebergArrival->name }}</span>
                                </div>
                                <div>
                                    <strong>Время в пути:</strong> {{ $voyage->travel_time }} ч
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                Назад к рейсам
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
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Выберите билеты</h5>
                            </div>
                            <div class="card-body">
                                @if($tickets->count() > 0)
                                    <div class="row row-cols-1 g-3">
                                        @foreach($tickets as $ticket)
                                            <div class="col">
                                                <div class="border rounded p-3 d-flex justify-content-between align-items-center hover-shadow transition">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="tickets[]" value="{{ $ticket->id }}" id="ticket-{{ $ticket->id }}">
                                                        <label class="form-check-label fw-medium" for="ticket-{{ $ticket->id }}">
                                                            {{ $ticket->type ?? 'Стандартный билет' }}
                                                            @if($ticket->place_number)
                                                                <span class="text-muted small">(Место {{ $ticket->place_number }})</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="h5 fw-bold text-primary mb-0">
                                                            {{ number_format($ticket->price, 0) }} ₽
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text text-danger mt-3">
                                        Обязательно выберите хотя бы один билет.
                                    </div>
                                @else
                                    <p class="text-muted text-center py-4">
                                        К сожалению, билеты на этот рейс закончились.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- РАЗВЛЕЧЕНИЯ --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Дополнительные развлечения</h5>
                            </div>
                            <div class="card-body">
                                @if($entertainments->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($entertainments as $ent)
                                            <div class="border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="fw-bold mb-1">{{ $ent->name }}</h6>
                                                        <small class="text-muted">{{ $ent->description ?? 'Дополнительная услуга' }}</small>
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        <span class="h6 fw-bold text-success">
                                                            {{ number_format($ent->price, 0) }} ₽
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 d-flex align-items-center gap-2">
                                                    <label class="small text-muted me-2">Кол-во:</label>
                                                    <select name="entertainments[{{ $loop->index }}][id]" class="form-select form-select-sm d-none">
                                                        <option value="{{ $ent->id }}">{{ $ent->id }}</option>
                                                    </select>
                                                    <input type="number" name="entertainments[{{ $loop->index }}][quantity]" value="0" min="0" max="10"
                                                           class="form-control form-control-sm w-20 quantity-input" data-price="{{ $ent->price }}">
                                                    <input type="hidden" name="entertainments[{{ $loop->index }}][id]" value="{{ $ent->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">Развлечения скоро появятся!</p>
                                @endif
                            </div>
                        </div>

                        {{-- ИТОГО --}}
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">Итого:</h5>
                                    <h4 class="fw-bold text-primary mb-0" id="total-price">0 ₽</h4>
                                </div>
                                <button type="submit" class="btn btn-success w-100 mt-3 fw-bold" id="submit-btn" disabled>
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
    const ticketCheckboxes = document.querySelectorAll('input[name="tickets[]"]');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalPriceEl = document.getElementById('total-price');
    const submitBtn = document.getElementById('submit-btn');

    function updateTotal() {
        let total = 0;

        // Билеты
        ticketCheckboxes.forEach(cb => {
            if (cb.checked) {
                const price = parseInt(cb.closest('.d-flex').querySelector('.text-primary').textContent.replace(/[^0-9]/g, ''));
                total += price;
            }
        });

        // Развлечения
        quantityInputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            const price = parseInt(input.dataset.price);
            total += qty * price;
        });

        totalPriceEl.textContent = total.toLocaleString() + ' ₽';
        submitBtn.disabled = !document.querySelector('input[name="tickets[]"]:checked');
    }

    // Слушатели
    ticketCheckboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    quantityInputs.forEach(input => input.addEventListener('input', updateTotal));

    // Инициализация
    updateTotal();
});
</script>
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    transform: translateY(-1px);
}
.w-20 {
    width: 70px !important;
}
</style>
@endsection

