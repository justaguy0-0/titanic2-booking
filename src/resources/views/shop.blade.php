@extends('head')
@section('title', 'ТИТАНИК 2 — Магазин билетов')
@section('main_content')
<div class="container-fluid px-0">

    <!-- Контент -->
    <div class="container py-5 shop-container">

        <!-- Компактный заголовок -->
        <div class="text-center mb-5 shop-page-header">
            <h1 class="display-5 fw-bold mb-2 shop-page-title">
                <i class="fas fa-ship me-3"></i>Доступные рейсы
            </h1>
            <p class="shop-page-subtitle mb-0">
                Выберите круиз и забронируйте билеты на незабываемое путешествие
            </p>
        </div>

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

        @if($voyages->count() > 0)
            <div class="row g-4">
                @foreach($voyages as $voyage)
                    <div class="col-lg-4 col-md-6 col-sm-12">
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
        @else
            <div class="text-center text-muted py-5">
                Рейсов пока нет. Скоро появятся!
            </div>
        @endif
    </div>
</div>
@endsection
