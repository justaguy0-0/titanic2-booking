@extends('head')
@section('title', 'ТИТАНИК 2 — Магазин билетов')
@section('main_content')
<div class="container-fluid px-0">

    <!-- Hero -->
    <section class="hero-section text-center py-5 d-flex align-items-center justify-content-center"
             style="
             background: linear-gradient(rgba(15,23,42,0.85), rgba(30,41,59,0.85)), url('/images/i.webp');
             background-size: cover;
             background-position: center;
             background-attachment: fixed;
             color: #fbbf24;
             min-height: 70vh;
             ">
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

        {{-- Рейсы --}}
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">
            ДОСТУПНЫЕ РЕЙСЫ
        </h2>

        @if($voyages->count() > 0)
            <div class="row g-4">
                @foreach($voyages as $voyage)
                    <div class="col-md-6 col-lg-4">
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
        @else
            <div class="text-center text-muted py-5">
                Рейсов пока нет. Скоро появятся!
            </div>
        @endif

        {{-- Развлечения --}}
        @if($entertainments->count() > 0)
            <h2 class="text-center mt-5 mb-4 fw-bold" style="color: #fbbf24;">
                РАЗВЛЕЧЕНИЯ НА БОРТУ
            </h2>
            <div class="row g-4">
                @foreach($entertainments as $entertainment)
                    <div class="col-md-6 col-lg-3">
                        <div class="card text-center border-0 shadow-sm"
                             style="background: #1e293b; color: #fcd34d; border: 1px solid #334155;">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $entertainment->name }}</h5>
                                <p class="display-6 fw-bold text-warning">
                                    {{ number_format($entertainment->price, 0) }} ₽
                                </p>
                                <small class="text-muted">Добавляется при бронировании</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .flight-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #334155;
    }
    .flight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(251, 191, 36, 0.25);
        border-color: #fbbf24;
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
    }
    body {
        background: #0f172a;
        color: #fcd34d;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection