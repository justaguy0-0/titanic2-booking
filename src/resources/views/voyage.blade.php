@extends('head')

@section('title', 'Путешествие на Титаник 2 - Маршрут и отзывы')

@section('main_content')
<div class="container-fluid px-0">

<!-- Секция маршрутов -->
<section class="py-5" style="background: #0f172a;">
    <div class="container">

        <div class="row">
            <!-- Левая часть - Карта -->
            <div class="col-lg-7">
                <div class="route-map mb-4"
                     style="height: 500px;
                            background: linear-gradient(rgba(15,23,42,0.8), rgba(30,41,59,0.8)),
                                        url('/images/country.png') center/cover;
                            border: 2px solid #fbbf24;
                            border-radius: 10px;
                            position: relative;">
                    <svg width="100%" height="100%" viewBox="0 0 800 500" style="background: transparent;">
                        <!-- Маршрутные линии -->
                        <path id="route1" d="M180,90 Q350,60 550,50" stroke="#fbbf24" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route2" d="M150,110 Q350,100 350,130" stroke="#f59e0b" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route3" d="M200,140 Q350,120 490,45" stroke="#d97706" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>
                        <path id="route4" d="M150,170 Q350,150 350,200" stroke="#dc2626" stroke-width="3" stroke-dasharray="5,5" fill="none" opacity="0" class="route-line"/>

                        <!-- Города отправления (левая сторона) -->
                        <g class="city" data-city="route1" data-name="Титаноград → Айсберг №1912" style="cursor: pointer;">
                            <circle cx="180" cy="90" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="200" y="95" fill="#fcd34d" font-size="14" font-weight="bold">Титаноград</text>
                        </g>

                        <g class="city" data-city="route2" data-name="Непотопинск → Полярная Обнимашка" style="cursor: pointer;">
                            <circle cx="150" cy="110" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="170" y="115" fill="#fcd34d" font-size="14" font-weight="bold">Непотопинск</text>
                        </g>

                        <g class="city" data-city="route3" data-name="Селфи-Харбор → Ледяная Глыба" style="cursor: pointer;">
                            <circle cx="200" cy="140" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="220" y="145" fill="#fcd34d" font-size="14" font-weight="bold">Селфи-Харбор</text>
                        </g>

                        <g class="city" data-city="route4" data-name="Вайс-Сити → Полярная Обнимашка" style="cursor: pointer;">
                            <circle cx="150" cy="170" r="10" fill="#fbbf24" stroke="#fff" stroke-width="2" class="city-dot"/>
                            <text x="170" y="175" fill="#fcd34d" font-size="14" font-weight="bold">Вайс-Сити</text>
                        </g>

                        <!-- Айсберги назначения (правая сторона) -->


                        <g class="iceberg" data-iceberg="route1" style="cursor: pointer;">
                            <circle cx="550" cy="50" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="570" y="55" fill="#bfdbfe" font-size="14" font-weight="bold">Айсберг №1912</text>
                        </g>

                        <g class="iceberg" data-iceberg="route2" style="cursor: pointer;">
                            <circle cx="350" cy="130" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="370" y="135" fill="#bfdbfe" font-size="14" font-weight="bold">Полярная Обнимашка</text>
                        </g>

                        <g class="iceberg" data-iceberg="route3" style="cursor: pointer;">
                            <circle cx="490" cy="45" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="370" y="50" fill="#bfdbfe" font-size="14" font-weight="bold">Ледяная Глыба</text>
                        </g>

                        <g class="iceberg" data-iceberg="route4" style="cursor: pointer;">
                            <circle cx="350" cy="200" r="10" fill="#93c5fd" stroke="#fff" stroke-width="2" class="iceberg-dot"/>
                            <text x="370" y="205" fill="#bfdbfe" font-size="14" font-weight="bold">Белый Убийца</text>
                        </g>
                    </svg>
                </div>
            </div>

            <!-- Правая часть - Список маршрутов -->
            <div class="col-lg-5">
                <!-- Остальной код без изменений -->
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

                        <div style="height: 150px; background: url('/images/1_1.jpeg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">

                        <div style="height: 150px; background: url('/images/2_1.jpeg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">

                        <div style="height: 150px; background: url('/images/3.jpeg') center/cover; border: 1px solid #fbbf24;"></div>
                    </div>
                    <div class="col-3">

                        <div style="height: 150px; background: url('/images/4.jpeg') center/cover; border: 1px solid #fbbf24;"></div>
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
