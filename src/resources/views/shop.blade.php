@extends('head')
@section('title', 'ТИТАНИК 2 — Магазин билетов')
@section('main_content')
<div class="container-fluid px-0">

    <!-- Hero -->
    <section class="hero-section text-center py-5 d-flex align-items-center justify-content-center">
        <div>
            <h1 class="display-4 fw-bold mb-3 shop-hero-title">
                Путешествия на айсберги
            </h1>
            <p class="lead mb-0 text-light">
                Незабываемые приключения среди ледяных просторов
            </p>
        </div>
    </section>

    <!-- Контент -->
    <div class="container py-5 shop-container">

        {{-- Сообщения --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 text-center shop-alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-4 text-center shop-alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Левая колонка - Рейсы -->
            <div class="col-lg-8">
                <h2 class="text-center mb-5 fw-bold shop-section-title">
                    ДОСТУПНЫЕ РЕЙСЫ
                </h2>

                @if($voyages->count() > 0)
                    <div class="row g-4">
                        @foreach($voyages->chunk(2) as $voyagePair)
                            <div class="col-12">
                                <div class="row g-4">
                                    @foreach($voyagePair as $voyage)
                                        <div class="col-md-6">
                                            <div class="card flight-card h-100 border-0 shadow-lg">

                                                <!-- Header -->
                                                <div class="card-header flight-card-header text-center py-3">
                                                    <h5 class="mb-0 flight-card-title">{{ $voyage->name }}</h5>
                                                   <small class="flight-card-route">
                                                        {{ $voyage->departurePlace->name }} → {{ $voyage->arrivalPlace->name }}
                                                    </small>
                                                </div>

                                                <!-- Body -->
                                                <div class="card-body flight-card-body text-center">
                                                    <div class="mb-4">
                                                        <div class="d-flex justify-content-around align-items-stretch gap-3">
                                                            <div class="flight-date-container flex-fill">
                                                                <div class="flight-date-label d-block mb-2">
                                                                    <i class="fas fa-ship me-1"></i> Отправление
                                                                </div>
                                                                <div class="flight-date-value">
                                                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y') }}
                                                                </div>
                                                                <div class="flight-date-value" style="font-size: 0.9rem;">
                                                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('H:i') }}
                                                                </div>
                                                            </div>
                                                            <div class="flight-date-container flex-fill">
                                                                <div class="flight-date-label d-block mb-2">
                                                                    <i class="fas fa-anchor me-1"></i> Прибытие
                                                                </div>
                                                                <div class="flight-date-value">
                                                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y') }}
                                                                </div>
                                                                <div class="flight-date-value" style="font-size: 0.9rem;">
                                                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="flight-duration">
                                                            <i class="fas fa-clock me-2"></i>
                                                            Длительность: {{ $voyage->travel_time }} ч
                                                        </span>
                                                    </div>
                                                    <h4 class="fw-bold flight-price mb-0">
                                                        {{ number_format($voyage->base_price, 0) }} ₽
                                                    </h4>
                                                </div>

                                                <!-- Footer -->
                                                <div class="card-footer flight-card-footer text-center">
                                                    <a href="{{ route('shop.select-tickets', $voyage->id) }}"
                                                       class="btn btn-gold fw-bold w-100 py-3">
                                                        <i class="fas fa-anchor me-2"></i>
                                                        ВЫБРАТЬ РЕЙС
                                                        <i class="fas fa-chevron-right ms-2"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        Рейсов пока нет. Скоро появятся!
                    </div>
                @endif
            </div>

            <!-- Правая колонка - Развлечения -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card entertainment-card border-0 shadow-lg">
                        <div class="card-header entertainment-card-header text-center py-3">
                            <h5 class="mb-0 entertainment-card-title">
                                <i class="fas fa-umbrella-beach me-2"></i>
                                РАЗВЛЕЧЕНИЯ НА БОРТУ
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            @if($entertainments->count() > 0)
                                <div class="accordion" id="entertainmentsAccordion">
                                    @foreach($entertainments->chunk(ceil($entertainments->count() / 3)) as $index => $entertainmentChunk)
                                        <div class="accordion-item entertainment-accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                <button class="accordion-button collapsed entertainment-accordion-button"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $index }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse{{ $index }}">
                                                    Группа развлечений {{ $index + 1 }}
                                                    <i class="fas fa-chevron-down ms-2"></i>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $index }}"
                                                 class="accordion-collapse collapse"
                                                 aria-labelledby="heading{{ $index }}"
                                                 data-bs-parent="#entertainmentsAccordion">
                                                <div class="accordion-body p-2">
                                                    @foreach($entertainmentChunk as $entertainment)
                                                        <div class="entertainment-item d-flex justify-content-between align-items-center py-2 px-3 mb-2 rounded">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold entertainment-name">
                                                                    {{ $entertainment->name }}
                                                                </h6>
                                                                <small class="text-muted">
                                                                    Добавляется при бронировании
                                                                </small>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="fw-bold entertainment-price">
                                                                    {{ number_format($entertainment->price, 0) }} ₽
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p>Развлечения скоро появятся</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer entertainment-card-footer text-center py-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Выберите развлечения при бронировании рейса
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
