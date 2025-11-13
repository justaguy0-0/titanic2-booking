@extends('head')
@section('title', 'ТИТАНИК 2 — Магазин билетов')
@section('main_content')
<div class="container-fluid px-0">

    <!-- Hero -->
    <section class="hero-section text-center py-5 d-flex align-items-center justify-content-center">
        <div>
            <h1 class="display-4 fw-bold mb-3" style="font-family: Georgia, serif;">
                Путешествия на айсберги
            </h1>
            <p class="lead mb-0 text-light">
                Незабываемые приключения среди ледяных просторов
            </p>
        </div>
    </section>

    <!-- Контент -->
    <div class="container py-5" style="background: #0f172a;">

        {{-- Сообщения --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 text-center" style="background: #1e293b; border: 1px solid #fbbf24; color: #fcd34d;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-4 text-center" style="background: #1e293b; border: 1px solid #fbbf24; color: #fcd34d;">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Левая колонка - Рейсы -->
            <div class="col-lg-8">
                <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">
                    ДОСТУПНЫЕ РЕЙСЫ
                </h2>

                @if($voyages->count() > 0)
                    <div class="row g-4">
                        @foreach($voyages->chunk(2) as $voyagePair)
                            <div class="col-12">
                                <div class="row g-4">
                                    @foreach($voyagePair as $voyage)
                                        <div class="col-md-6">
                                            <div class="card flight-card h-100 border-0 shadow-lg"
                                                 style="background: #1e293b; border-radius: 12px; overflow: hidden;">

                                                <!-- Header -->
                                                <div class="card-header text-center py-3"
                                                    style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                                                    <h5 class="mb-0" style="color: #fbbf24;">{{ $voyage->name }}</h5>
                                                   <small style="color: #fcd34d;">
                                                        {{ $voyage->departurePlace->name }} → {{ $voyage->arrivalPlace->name }}
                                                    </small>
                                                </div>

                                                <!-- Body -->
                                                <div class="card-body text-center" style="color: #fcd34d;">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <small class="text-muted">Отправление</small>
                                                                <p class="mb-0 fw-bold">
                                                                    {{ \Carbon\Carbon::parse($voyage->departure_date)->format('d.m.Y H:i') }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted">Прибытие</small>
                                                                <p class="mb-0 fw-bold">
                                                                    {{ \Carbon\Carbon::parse($voyage->arrival_date)->format('d.m.Y H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="fw-semibold mb-1">Длительность: {{ $voyage->travel_time }} ч</p>
                                                    <h4 class="fw-bold text-warning">
                                                        {{ number_format($voyage->base_price, 0) }} ₽
                                                    </h4>
                                                </div>

                                                <!-- Footer -->
                                                <div class="card-footer bg-transparent text-center"
                                                     style="border-top: 1px solid #334155;">
                                                    <a href="{{ route('shop.voyage', $voyage->id) }}"
                                                       class="btn btn-gold btn-sm fw-bold w-100">
                                                        ВЫБРАТЬ РЕЙС
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
                    <div class="card border-0 shadow-lg" style="background: #1e293b; border-radius: 12px;">
                        <div class="card-header text-center py-3"
                             style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">
                                <i class="fas fa-umbrella-beach me-2"></i>
                                РАЗВЛЕЧЕНИЯ НА БОРТУ
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            @if($entertainments->count() > 0)
                                <div class="accordion" id="entertainmentsAccordion">
                                    @foreach($entertainments->chunk(ceil($entertainments->count() / 3)) as $index => $entertainmentChunk)
                                        <div class="accordion-item" style="background: transparent; border: none;">
                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                <button class="accordion-button collapsed"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $index }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse{{ $index }}"
                                                        style="background: #334155; color: #fbbf24; border: none;">
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
                                                        <div class="d-flex justify-content-between align-items-center py-2 px-3 mb-2 rounded"
                                                             style="background: rgba(251, 191, 36, 0.1); border-left: 3px solid #fbbf24;">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold" style="color: #fcd34d;">
                                                                    {{ $entertainment->name }}
                                                                </h6>
                                                                <small class="text-muted">
                                                                    Добавляется при бронировании
                                                                </small>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="fw-bold text-warning">
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

                        <div class="card-footer text-center py-3"
                             style="background: #334155; border-top: 1px solid #fbbf24;">
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
