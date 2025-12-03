@extends('head')

@section('title', 'О нас - Титаник 2')

@section('main_content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold mb-3 hero-title">О Титанике 2</h1>
            <p class="lead mb-4 hero-subtitle">Легенда возвращается в будущее</p>
        </div>
    </div>

    {{-- Карусель с изображениями --}}
<div class="row mb-5">
    <div class="col-12">
        <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="3"></button>
            </div>

            <div class="carousel-inner rounded-3 overflow-hidden" style="height: 500px;">
                {{-- Слайд 1 --}}
                <div class="carousel-item active h-100">
                    <div class="w-100 h-100"
                         style="background: linear-gradient(rgba(30, 41, 59, 0.3), rgba(30, 41, 59, 0.3)),
                                url('{{ asset('images/about_5.png') }}')
                                center center / cover no-repeat;">
                    </div>

                </div>

                {{-- Слайд 2 --}}
                <div class="carousel-item h-100">
                    <div class="w-100 h-100"
                         style="background: linear-gradient(rgba(30, 41, 59, 0.3), rgba(30, 41, 59, 0.3)),
                                url('{{ asset('images/about_3.png') }}')
                                center center / cover no-repeat;">
                    </div>

                </div>

                {{-- Слайд 3 --}}
                <div class="carousel-item h-100">
                    <div class="w-100 h-100"
                         style="background: linear-gradient(rgba(30, 41, 59, 0.3), rgba(30, 41, 59, 0.3)),
                                url('{{ asset('images/about_2.png') }}')
                                center center / cover no-repeat;">
                    </div>

                </div>

                {{-- Слайд 4 --}}
                <div class="carousel-item h-100">
                    <div class="w-100 h-100"
                         style="background: linear-gradient(rgba(30, 41, 59, 0.3), rgba(30, 41, 59, 0.3)),
                                url('{{ asset('images/about_1.png') }}')
                                center center / cover no-repeat;">
                    </div>

                </div>
            </div>


            <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>


    <div class="row g-4 mb-5">

        <div class="col-lg-6">
            <div class="about-section p-4 h-100 rounded" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #fbbf24;">
                <div class="section-header mb-4 text-center">
                    <h2 class="text-gold mb-2">История создания легенды</h2>
                    <div class="divider mx-auto" style="width: 100px; height: 2px; background: #fbbf24;"></div>
                </div>

                <div class="history-content">
                    <div class="timeline-item mb-4 pb-3 border-bottom border-secondary">
                        <h5 class="text-gold mb-2"> Идея и вдохновение</h5>
                        <p class="text-light mb-0">
                            Идея Титаника 2 проснулась после просмотра знаменитого фильма, когда мы увидели невероятный ажиотаж
                            и любовь людей к этой истории. Нам показалось, что будет интересно возродить легенду, но в современном
                            прочтении - с технологиями будущего и абсолютной безопасностью.
                        </p>
                    </div>

                    <div class="timeline-item mb-4 pb-3 border-bottom border-secondary">
                        <h5 class="text-gold mb-2"> От чертежей к реальности</h5>
                        <p class="text-light mb-0">
                            Мы начали с тщательного изучения архивных чертежей оригинального Титаника, но быстро поняли,
                            что простое копирование - не наш путь. Вместо этого мы создали корабль, который сохраняет
                            дух и элегантность 1912 года, но оснащен по последнему слову техники.
                        </p>
                    </div>

                    <div class="timeline-item">
                        <h5 class="text-gold mb-2"> Команда мечты</h5>
                        <p class="text-light mb-0">
                            Ничего бы не получилось без нашей замечательной команды - опытных капитанов, талантливых поваров,
                            отважных моряков, дизайнеров и инженеров. Каждый внес свой вклад в создание этого уникального проекта,
                            превратив мечту в реальность.
                        </p>
                    </div>
                </div>

                <div class="quote-section mt-4 p-3 rounded" style="background: rgba(15, 23, 42, 0.6); border-left: 3px solid #fbbf24;">
                    <p class="text-light mb-0">
                        <i>"Сегодня Титаник 2 - это не просто корабль, а символ того, как можно уважать историю,
                        одновременно смотря в будущее."</i>
                    </p>
                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="about-section p-4 h-100 rounded" style="background: rgba(30, 41, 59, 0.8); border: 1px solid #3b82f6;">
                <div class="section-header mb-4 text-center">
                    <h2 class="text-blue mb-2">Наши планы на будущее</h2>
                    <div class="divider mx-auto" style="width: 100px; height: 2px; background: #3b82f6;"></div>
                </div>

                <div class="plans-grid">
                    <div class="plan-card p-3 mb-3 rounded" style="background: rgba(15, 23, 42, 0.6);">
                        <div class="d-flex align-items-start">
                            <div class="plan-icon me-3" style="color: #3b82f6;">
                                <i class="fas fa-globe-americas fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-blue mb-1">Глобальные маршруты</h6>
                                <p class="text-light mb-0 small">
                                    Расширение географии путешествий - от полярных экспедиций до тропических круизов
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="plan-card p-3 mb-3 rounded" style="background: rgba(15, 23, 42, 0.6);">
                        <div class="d-flex align-items-start">
                            <div class="plan-icon me-3" style="color: #3b82f6;">
                                <i class="fas fa-theater-masks fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-blue mb-1">Культурные программы</h6>
                                <p class="text-light mb-0 small">
                                    Театральные постановки, исторические реконструкции и образовательные лекции на борту
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="plan-card p-3 mb-3 rounded" style="background: rgba(15, 23, 42, 0.6);">
                        <div class="d-flex align-items-start">
                            <div class="plan-icon me-3" style="color: #3b82f6;">
                                <i class="fas fa-shield-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-blue mb-1">Инновации в безопасности</h6>
                                <p class="text-light mb-0 small">
                                    Разработка умных систем предсказания погоды и автоматического управления судном
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="plan-card p-3 mb-3 rounded" style="background: rgba(15, 23, 42, 0.6);">
                        <div class="d-flex align-items-start">
                            <div class="plan-icon me-3" style="color: #3b82f6;">
                                <i class="fas fa-vr-cardboard fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-blue mb-1">Технологии гостеприимства</h6>
                                <p class="text-light mb-0 small">
                                    Внедрение виртуальной реальности для экскурсий и персонализированного сервиса для каждого гостя
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stats-section mt-4 p-3 rounded" style="background: rgba(15, 23, 42, 0.6);">
                    <h6 class="text-blue mb-3">Наши достижения</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-number text-blue" style="font-size: 1.5rem; font-weight: bold;">5+</div>
                            <div class="stat-label text-light small">Лет в разработке</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number text-blue" style="font-size: 1.5rem; font-weight: bold;">200+</div>
                            <div class="stat-label text-light small">Сотрудников</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number text-blue" style="font-size: 1.5rem; font-weight: bold;">10k+</div>
                            <div class="stat-label text-light small">Довольных гостей</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="image-container position-relative">
                <img src="{{ asset('images/about_5.png') }}"
                    alt="Титаник 2 в море"
                    class="img-fluid rounded-3 shadow"
                    style="width: 100%; height: 300px; object-fit: cover;">
                <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 rounded-3"
                    style="background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.8) 100%);">
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 p-3 text-center">
                    <p class="text-light mb-0">«Путешествие, которое изменит ваше представление о морских приключениях»</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
