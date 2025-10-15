@extends('head')

@section('main_content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <section class="hero-section text-center py-5" style="
    background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(30, 41, 59, 0.8)), 
                url('/images/i.webp');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #fbbf24;
    min-height: 80vh;
    display: flex;
    align-items: center;">
    
</section>

    <!-- Available Flights Section -->
    <section class="py-5" style="background: #0f172a;">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">ДОСТУПНЫЕ РЕЙСЫ</h2>
            
            <div class="row g-4">
                <!-- Flight 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card flight-card h-100 border-0 shadow-lg" style="background: #1e293b;">
                        <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">РЕЙС GK-012</h5>
                            <small style="color: #fcd34d;">Трансатлантический</small>
                        </div>
                        <div class="card-body text-center" style="color: #fcd34d;">
                            <div class="flight-route mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #fbbf24;">Лондон</h6>
                                        <small>Великобритания</small>
                                    </div>
                                    <div class="flight-arrow mx-3">
                                        <i class="fas fa-arrow-right fa-2x" style="color: #fbbf24;"></i>
                                    </div>
                                    <div>
                                        <h6 style="color: #fbbf24;">Нью-Йорк</h6>
                                        <small>США</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flight-details">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small>Отправление</small>
                                        <p class="mb-0 fw-bold">10 Апр 2042</p>
                                    </div>
                                    <div class="col-6">
                                        <small>Длительность</small>
                                        <p class="mb-0 fw-bold">5 дней</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center" style="border-top: 1px solid #334155;">
                            <button class="btn btn-gold btn-sm fw-bold">ВЫБРАТЬ РЕЙС</button>
                        </div>
                    </div>
                </div>

                <!-- Flight 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card flight-card h-100 border-0 shadow-lg" style="background: #1e293b;">
                        <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #334155, #475569); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">РЕЙС GK-013</h5>
                            <small style="color: #fcd34d;">Североамериканский</small>
                        </div>
                        <div class="card-body text-center" style="color: #fcd34d;">
                            <div class="flight-route mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #fbbf24;">Лондон</h6>
                                        <small>Великобритания</small>
                                    </div>
                                    <div class="flight-arrow mx-3">
                                        <i class="fas fa-arrow-right fa-2x" style="color: #fbbf24;"></i>
                                    </div>
                                    <div>
                                        <h6 style="color: #fbbf24;">Филадельфия</h6>
                                        <small>США</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flight-details">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small>Отправление</small>
                                        <p class="mb-0 fw-bold">15 Апр 2042</p>
                                    </div>
                                    <div class="col-6">
                                        <small>Длительность</small>
                                        <p class="mb-0 fw-bold">6 дней</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center" style="border-top: 1px solid #334155;">
                            <button class="btn btn-gold btn-sm fw-bold">ВЫБРАТЬ РЕЙС</button>
                        </div>
                    </div>
                </div>

                <!-- Flight 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card flight-card h-100 border-0 shadow-lg" style="background: #1e293b;">
                        <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #475569, #64748b); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">РЕЙС GK-014</h5>
                            <small style="color: #fcd34d;">Канадский экспресс</small>
                        </div>
                        <div class="card-body text-center" style="color: #fcd34d;">
                            <div class="flight-route mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #fbbf24;">Лондон</h6>
                                        <small>Великобритания</small>
                                    </div>
                                    <div class="flight-arrow mx-3">
                                        <i class="fas fa-arrow-right fa-2x" style="color: #fbbf24;"></i>
                                    </div>
                                    <div>
                                        <h6 style="color: #fbbf24;">Галифакс</h6>
                                        <small>Канада</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flight-details">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small>Отправление</small>
                                        <p class="mb-0 fw-bold">20 Апр 2042</p>
                                    </div>
                                    <div class="col-6">
                                        <small>Длительность</small>
                                        <p class="mb-0 fw-bold">4 дня</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center" style="border-top: 1px solid #334155;">
                            <button class="btn btn-gold btn-sm fw-bold">ВЫБРАТЬ РЕЙС</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Flights Row -->
            <div class="row g-4 mt-2">
                <!-- Flight 4 -->
                <div class="col-md-6">
                    <div class="card flight-card h-100 border-0 shadow" style="background: #1e293b;">
                        <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #1e293b, #334155); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">РЕЙС GK-015</h5>
                            <small style="color: #fcd34d;">Восточное побережье</small>
                        </div>
                        <div class="card-body text-center" style="color: #fcd34d;">
                            <div class="flight-route mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #fbbf24;">Лондон</h6>
                                        <small>Великобритания</small>
                                    </div>
                                    <div class="flight-arrow mx-3">
                                        <i class="fas fa-arrow-right fa-2x" style="color: #fbbf24;"></i>
                                    </div>
                                    <div>
                                        <h6 style="color: #fbbf24;">Балтимор</h6>
                                        <small>США</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flight-details">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small>Отправление</small>
                                        <p class="mb-0 fw-bold">25 Апр 2042</p>
                                    </div>
                                    <div class="col-6">
                                        <small>Длительность</small>
                                        <p class="mb-0 fw-bold">5 дней</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center" style="border-top: 1px solid #334155;">
                            <button class="btn btn-outline-gold btn-sm fw-bold">ВЫБРАТЬ РЕЙС</button>
                        </div>
                    </div>
                </div>

                <!-- Flight 5 -->
                <div class="col-md-6">
                    <div class="card flight-card h-100 border-0 shadow" style="background: #1e293b;">
                        <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #334155, #475569); border-bottom: 2px solid #fbbf24;">
                            <h5 class="mb-0" style="color: #fbbf24;">РЕЙС GK-016</h5>
                            <small style="color: #fcd34d;">Европейский экспресс</small>
                        </div>
                        <div class="card-body text-center" style="color: #fcd34d;">
                            <div class="flight-route mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #fbbf24;">Нью-Йорк</h6>
                                        <small>США</small>
                                    </div>
                                    <div class="flight-arrow mx-3">
                                        <i class="fas fa-arrow-right fa-2x" style="color: #fbbf24;"></i>
                                    </div>
                                    <div>
                                        <h6 style="color: #fbbf24;">Лондон</h6>
                                        <small>Великобритания</small>
                                    </div>
                                </div>
                            </div>
                            <div class="flight-details">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small>Отправление</small>
                                        <p class="mb-0 fw-bold">30 Апр 2042</p>
                                    </div>
                                    <div class="col-6">
                                        <small>Длительность</small>
                                        <p class="mb-0 fw-bold">5 дней</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center" style="border-top: 1px solid #334155;">
                            <button class="btn btn-outline-gold btn-sm fw-bold">ВЫБРАТЬ РЕЙС</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<section class="hero-section text-center py-5" style="
    background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(30, 41, 59, 0.8)), 
                url('/images/i.webp');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #fbbf24;
    min-height: 80vh;
    display: flex;
    align-items: center;">
    
</section>

<style>
    .flight-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #334155;
    }
    
    .flight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(251, 191, 36, 0.2) !important;
        border-color: #fbbf24;
    }
    
    .btn-gold {
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        border: none;
        color: #1e293b;
        font-weight: bold;
    }
    
    .btn-gold:hover {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: #1e293b;
    }
    
    .btn-outline-gold {
        border: 2px solid #fbbf24;
        color: #fbbf24;
        font-weight: bold;
        background: transparent;
    }
    
    .btn-outline-gold:hover {
        background: #fbbf24;
        color: #1e293b;
    }
    
    body {
        background: #0f172a;
        color: #fcd34d;
    }
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection