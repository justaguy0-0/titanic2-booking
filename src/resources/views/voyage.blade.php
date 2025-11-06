@extends('head')

@section('title', 'Путешествие на Титаник 2 - Маршрут и отзывы')

@section('main_content')
<div class="container-fluid px-0">
    
    <section class="hero-section text-center py-5 d-flex align-items-center justify-content-center"
             style="background: linear-gradient(rgba(15,23,42,0.85), rgba(30,41,59,0.85)), url('/images/i.webp'); background-size: cover; background-position: center; background-attachment: fixed; color: #fbbf24; min-height: 60vh;">
        <div>
            <h1 class="display-4 fw-bold mb-3" style="font-family: Georgia, serif;">Легендарный маршрут</h1>
           
        </div>
        </section>

<!-- Секция маршрутов -->
<section class="py-5" style="background: #0f172a;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">МАРШРУТЫ ЭКСПЕДИЦИЙ</h2>
        
        <div class="row">
            <!-- Левая часть - Карта -->
            <div class="col-lg-7">
                <div class="route-map mb-4" style="height: 500px; background: #1e293b; border: 2px solid #fbbf24; border-radius: 10px; position: relative;">
                    <svg width="100%" height="100%" viewBox="0 0 800 500" style="background: #0f172a;">
                        <!-- Атлантический океан -->
                        <rect width="800" height="500" fill="#1e293b"/>
                        
                        <!-- Маршрутные линии (изначально скрыты) -->
                        <path id="route1" d="M100,100 Q400,50 700,400" stroke="#fbbf24" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route2" d="M150,150 Q400,100 650,350" stroke="#f59e0b" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route3" d="M200,200 Q400,150 600,300" stroke="#d97706" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route4" d="M250,250 Q400,200 550,250" stroke="#dc2626" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        
                        <!-- Города отправления (левая сторона) -->
                        <g class="city" data-city="route1" data-name="Титаноград → Айсберг №1912" style="cursor: pointer;">
                            <circle cx="100" cy="100" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="120" y="105" fill="#fcd34d" font-size="14" font-weight="bold"> Титаноград</text>
                        </g>
                        
                        <g class="city" data-city="route2" data-name="Непотопинск → Полярная Обнимашка" style="cursor: pointer;">
                            <circle cx="150" cy="150" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="170" y="155" fill="#fcd34d" font-size="14" font-weight="bold"> Непотопинск</text>
                        </g>
                        
                        <g class="city" data-city="route3" data-name="Селфи-Харбор → Ледяная Глыба" style="cursor: pointer;">
                            <circle cx="200" cy="200" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="220" y="205" fill="#fcd34d" font-size="14" font-weight="bold"> Селфи-Харбор</text>
                        </g>
                        
                        <g class="city" data-city="route4" data-name="Вайс-Сити → Полярная Обнимашка" style="cursor: pointer;">
                            <circle cx="250" cy="250" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="270" y="255" fill="#fcd34d" font-size="14" font-weight="bold"> Вайс-Сити</text>
                        </g>
                        
                        <!-- Айсберги назначения (правая сторона) -->
                        <g class="iceberg" data-iceberg="route1" style="cursor: pointer;">
                            <circle cx="700" cy="400" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="570" y="405" fill="#bfdbfe" font-size="14" font-weight="bold"> Айсберг №1912</text>
                        </g>
                        
                        <g class="iceberg" data-iceberg="route2" style="cursor: pointer;">
                            <circle cx="650" cy="350" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="470" y="355" fill="#bfdbfe" font-size="14" font-weight="bold"> Полярная Обнимашка</text>
                        </g>
                        
                        <g class="iceberg" data-iceberg="route3" style="cursor: pointer;">
                            <circle cx="600" cy="300" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="450" y="305" fill="#bfdbfe" font-size="14" font-weight="bold"> Ледяная Глыба</text>
                        </g>
                        
                        <g class="iceberg" data-iceberg="route4" style="cursor: pointer;">
                            <circle cx="550" cy="250" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="380" y="255" fill="#bfdbfe" font-size="14" font-weight="bold"> Белый Убийца</text>
                        </g>
                    </svg>
                    
                    
                </div>
            </div>

            
            <div class="col-lg-5">
                <div class="routes-list">
                    
                    <div class="route-item mb-2 p-2 rounded" style="background: #1e293b; border: 1px solid #334155; transition: all 0.3s ease;" data-route="route1">
                        <div class="route-main d-flex justify-content-between align-items-start mb-1">
                            <h6 style="color: #fbbf24; margin: 0; font-size: 0.9rem; line-height: 1.2;"> Титаноград → Айсберг №1912</h6>
                            <small style="color: #fbbf24; font-weight: bold; white-space: nowrap;">120 ч</small>
                        </div>
                        <div class="route-details">
                            <small style="color: #94a3b8;">14.11 - 19.11.2025</small>
                        </div>
                    </div>

                    <div class="route-item mb-2 p-2 rounded" style="background: #1e293b; border: 1px solid #334155; transition: all 0.3s ease;" data-route="route2">
                        <div class="route-main d-flex justify-content-between align-items-start mb-1">
                            <h6 style="color: #fbbf24; margin: 0; font-size: 0.9rem; line-height: 1.2;"> Непотопинск → Полярная Обнимашка</h6>
                            <small style="color: #fbbf24; font-weight: bold; white-space: nowrap;">192 ч</small>
                        </div>
                        <div class="route-details">
                            <small style="color: #94a3b8;">24.11 - 02.12.2025</small>
                        </div>
                    </div>

                    <div class="route-item mb-2 p-2 rounded" style="background: #1e293b; border: 1px solid #334155; transition: all 0.3s ease;" data-route="route3">
                        <div class="route-main d-flex justify-content-between align-items-start mb-1">
                            <h6 style="color: #fbbf24; margin: 0; font-size: 0.9rem; line-height: 1.2;"> Селфи-Харбор → Ледяная Глыба</h6>
                            <small style="color: #fbbf24; font-weight: bold; white-space: nowrap;">144 ч</small>
                        </div>
                        <div class="route-details">
                            <small style="color: #94a3b8;">04.12 - 10.12.2025</small>
                        </div>
                    </div>

                    <div class="route-item mb-2 p-2 rounded" style="background: #1e293b; border: 1px solid #334155; transition: all 0.3s ease;" data-route="route4">
                        <div class="route-main d-flex justify-content-between align-items-start mb-1">
                            <h6 style="color: #fbbf24; margin: 0; font-size: 0.9rem; line-height: 1.2;"> Вайс-Сити → Полярная Обнимашка</h6>
                            <small style="color: #fbbf24; font-weight: bold; white-space: nowrap;">79 ч</small>
                        </div>
                        <div class="route-details">
                            <small style="color: #94a3b8;">12.12 - 15.12.2025</small>
                        </div>
                    </div>
                </div>

                <!-- Статистика -->
                <div class="route-stats mt-3 p-3 rounded text-center" style="background: #1e293b; border: 1px solid #334155;">
                    <div class="row">
                        <div class="col-4">
                            <h5 style="color: #fbbf24; margin: 0;">8</h5>
                            <small style="color: #94a3b8;">Маршрутов</small>
                        </div>
                        <div class="col-4">
                            <h5 style="color: #fbbf24; margin: 0;">6</h5>
                            <small style="color: #94a3b8;">Пунктов</small>
                        </div>
                        <div class="col-4">
                            <h5 style="color: #fbbf24; margin: 0;">120ч</h5>
                            <small style="color: #94a3b8;">В среднем</small>
                        </div>
                    </div>
                </div>

                <!-- Кнопка -->
                <div class="text-center mt-3">
                    <a href="{{ route('shop') }}" class="btn btn-gold btn-sm fw-bold">
                         Все маршруты и бронирование
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-5" style="background: #1e293b;">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">ОТЗЫВЫ ПУТЕШЕСТВЕННИКОВ</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="review-card p-4 rounded h-100" style="background: #0f172a; border: 1px solid #fbbf24;">
                    <div class="review-header mb-3">
                        <div class="d-flex align-items-center">
                            
                            <div class="review-avatar me-3" style="width: 50px; height: 50px; background: url('/images/people1.jpg') center/cover; border-radius: 50%; border: 2px solid #fbbf24;"></div>
                            <div>
                                <h6 style="color: #fbbf24; margin: 0;">Анна и Михаил</h6>
                                <small style="color: #94a3b8;">Юбилейное путешествие</small>
                            </div>
                        </div>
                    </div>
                    <p style="color: #fcd34d; font-style: italic;">"Отметили 20 лет свадьбы на борту Титаника 2. Каждая деталь была продумана! От романтического ужина на палубе до экскурсии по историческим местам. Персонал сделал наше путешествие truly незабываемым."</p>
                    <div class="rating" style="color: #fbbf24;">⭐⭐⭐⭐⭐</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="review-card p-4 rounded h-100" style="background: #0f172a; border: 1px solid #fbbf24;">
                    <div class="review-header mb-3">
                        <div class="d-flex align-items-center">
                            
                            <div class="review-avatar me-3" style="width: 50px; height: 50px; background: url('/images/people2.jpg') center/cover; border-radius: 50%; border: 2px solid #fbbf24;"></div>
                            <div>
                                <h6 style="color: #fbbf24; margin: 0;">Семья Петровых</h6>
                                <small style="color: #94a3b8;">Семейный отдых</small>
                            </div>
                        </div>
                    </div>
                    <p style="color: #fcd34d; font-style: italic;">"Дети в восторге от детского клуба! Мы смогли полноценно отдохнуть, пока они были под профессиональным присмотром. Развлечения на любой вкус - от исторических лекций до современных шоу. Обязательно вернемся!"</p>
                    <div class="rating" style="color: #fbbf24;">⭐⭐⭐⭐⭐</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="review-card p-4 rounded h-100" style="background: #0f172a; border: 1px solid #fbbf24;">
                    <div class="review-header mb-3">
                        <div class="d-flex align-items-center">
                            
                            <div class="review-avatar me-3" style="width: 50px; height: 50px; background: url('/images/people3.webp') center/cover; border-radius: 50%; border: 2px solid #fbbf24;"></div>
                            <div>
                                <h6 style="color: #fbbf24; margin: 0;">Олег, бизнесмен</h6>
                                <small style="color: #94a3b8;">Деловая поездка</small>
                            </div>
                        </div>
                    </div>
                    <p style="color: #fcd34d; font-style: italic;">"Сочетал бизнес-встречи с отдыхом. Wi-Fi отличный по всему лайнеру, есть тихие зоны для работы. Спа-комплекс помог восстановить силы после переговоров. Идеальное сочетание работы и отдыха!"</p>
                    <div class="rating" style="color: #fbbf24;">⭐⭐⭐⭐☆</div>
                </div>
            </div>
        </div>

       
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="text-center mb-4" style="color: #fbbf24;">Моменты с прошлых рейсов</h4>
                <div class="row g-2">
                    <div class="col-3">
                        
                        <div style="height: 150px; background: url('/images/gallery/cruise-1.jpg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">
                        
                        <div style="height: 150px; background: url('/images/gallery/cruise-2.jpg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">
                        
                        <div style="height: 150px; background: url('/images/gallery/cruise-3.jpg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">
                        
                        <div style="height: 150px; background: url('/images/gallery/cruise-4.jpg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    
    <section class="py-5" style="background: #0f172a;">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold" style="color: #fbbf24; font-family: Georgia, serif;">ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        
                        <div class="accordion-item mb-3" style="background: #1e293b; border: 1px solid #fbbf24;">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: #1e293b; color: #fbbf24;">
                                     Что взять с собой в круиз?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #fcd34d;">
                                    <strong>Обязательно:</strong> паспорт, медицинская страховка, кредитная карта<br>
                                    <strong>Одежда:</strong> вечерние наряды для ужинов, спортивная форма, купальник, теплая одежда для палубы<br>
                                    <strong>Дополнительно:</strong> фотоаппарат, лекарства, адаптеры для розеток
                                </div>
                            </div>
                        </div>

                       
                        <div class="accordion-item mb-3" style="background: #1e293b; border: 1px solid #fbbf24;">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: #1e293b; color: #fbbf24;">
                                     Правила безопасности на борту
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #fcd34d;">
                                    • Обязательное участие в учебной тревоге в первый день<br>
                                    • Соблюдение правил поведения в общественных зонах<br>
                                    • Запрещено курить вне специально отведенных мест<br>
                                    • Дети до 12 лет только в сопровождении взрослых<br>
                                    • Использование спасательных жилетов по требованию
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item mb-3" style="background: #1e293b; border: 1px solid #fbbf24;">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: #1e293b; color: #fbbf24;">
                                     Что включено в стоимость?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #fcd34d;">
                                    <strong>Включено:</strong> проживание в каюте, 3-разовое питание, базовые развлечения, доступ в бассейн и спортзал, детский клуб<br>
                                    <strong>Дополнительно:</strong> спа-процедуры, премиум алкоголь, экскурсии, специальные ужины
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item mb-3" style="background: #1e293b; border: 1px solid #fbbf24;">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: #1e293b; color: #fbbf24;">
                                     Можно ли отменить бронирование?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="color: #fcd34d;">
                                    • За 60+ дней до отправления - полный возврат<br>
                                    • За 30-59 дней - возврат 50%<br>
                                    • За 15-29 дней - возврат 25%<br>
                                    • Менее 15 дней - возврат не предусмотрен<br>
                                    * Страхование отмены доступно при бронировании
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-5" style="background: #1e293b;">
        <div class="container text-center">
            <h3 style="color: #fbbf24;" class="mb-4">Готовы к незабываемому путешествию?</h3>
            <p style="color: #fcd34d;" class="mb-4">Присоединяйтесь к легенде и станьте частью истории Титаника 2</p>
            <a href="{{ route('shop') }}" class="btn btn-gold btn-lg fw-bold px-5 py-3">
                 ВЫБРАТЬ РЕЙС
            </a>
        </div>
    </section>
</div>

<style>
    .btn-gold {
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        border: none;
        color: #1e293b;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .btn-gold:hover {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
    }

    .accordion-button:not(.collapsed) {
        background: #334155 !important;
        color: #fbbf24 !important;
    }

    .review-card {
        transition: transform 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
    }
    .btn-gold {
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        border: none;
        color: #1e293b;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .btn-gold:hover {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
    }

    .route-item:hover, .route-item.active {
        border-color: #fbbf24 !important;
        background: #334155 !important;
        transform: translateX(5px);
    }

    .city-dot:hover, .iceberg-dot:hover {
        r: 12;
        filter: drop-shadow(0 0 8px currentColor);
    }

    .route-line {
        transition: opacity 0.5s ease;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const routeItems = document.querySelectorAll('.route-item');
    const routeLines = document.querySelectorAll('.route-line');
    const cityDots = document.querySelectorAll('.city-dot');
    const icebergDots = document.querySelectorAll('.iceberg-dot');

    // Функция для показа маршрута
    function showRoute(routeId) {
        // Скрываем все маршруты
        routeLines.forEach(line => {
            line.style.opacity = '0';
        });
        
        // Показываем выбранный маршрут
        const selectedRoute = document.getElementById(routeId);
        if (selectedRoute) {
            selectedRoute.style.opacity = '1';
        }
        
        // Подсвечиваем активный элемент в списке
        routeItems.forEach(item => {
            item.classList.remove('active');
        });
        const activeItem = document.querySelector(`[data-route="${routeId}"]`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
    }

    // Обработчики для точек на карте
    cityDots.forEach(dot => {
        dot.addEventListener('click', function() {
            const routeId = this.parentElement.getAttribute('data-city');
            showRoute(routeId);
        });
    });

    icebergDots.forEach(dot => {
        dot.addEventListener('click', function() {
            const routeId = this.parentElement.getAttribute('data-iceberg');
            showRoute(routeId);
        });
    });

    // Обработчики для элементов списка
    routeItems.forEach(item => {
        item.addEventListener('click', function() {
            const routeId = this.getAttribute('data-route');
            showRoute(routeId);
        });
    });

    // Показываем первый маршрут по умолчанию
    showRoute('route1');
});
</script>
@endsection