<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>

    {{-- Стили из app.css и скрипты из app.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Дополнительные стили --}}
    @stack('styles')
</head>
<body class="@if(request()->is('login') || request()->is('register') || request()->is('password.request*')) auth-bg @endif" style="background: #0f172a; margin: 0; padding: 0;">

    {{-- Шапка для авторизованных пользователей --}}
    @if (Auth::check())
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3"
                style="background: linear-gradient(90deg, #1e293b 0%, #334155 50%, #1e293b 100%); border-bottom: 2px solid #fbbf24;">

            <div class="col-md-3 mb-2 mb-md-0 ps-3">
                <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                    <div class="ms-2">
                        <h1 class="mb-0" style="font-family: Georgia, serif; font-size: 1.5rem; color: #fbbf24; letter-spacing: 2px; font-weight: bold;">ТИТАНИК 2</h1>
                        <p class="mb-0" style="font-family: Georgia, serif; font-size: 0.75rem; color: #fcd34d; font-style: italic;">Плавание сквозь Время</p>
                    </div>
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="{{ route('home') }}" class="nav-link px-3" style="color: #fbbf24;">Главная</a></li>
                <li><a href="{{ route('shop') }}" class="nav-link px-3" style="color: #fbbf24;">Рейсы</a></li>
                <li><a href="{{ route('dashboard') }}" class="nav-link px-3" style="color: #fbbf24;">Личный кабинет</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3">
                <span class="me-3">
                    <a href="{{ route('dashboard') }}" class="profile-link" style="color: #fbbf24;">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </a>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">Выйти</button>
                </form>
            </div>
        </header>

    {{-- Шапка для гостей (только на публичных страницах, кроме login/register/password) --}}
    @elseif (!request()->routeIs('login') && !request()->routeIs('register') && !request()->is('password.request*'))
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3"
                style="background: linear-gradient(90deg, #1e293b 0%, #334155 50%, #1e293b 100%); border-bottom: 2px solid #fbbf24;">

            <div class="col-md-3 mb-2 mb-md-0 ps-3">
                <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                    <div class="ms-2">
                        <h1 class="mb-0" style="font-family: Georgia, serif; font-size: 1.5rem; color: #fbbf24; letter-spacing: 2px; font-weight: bold;">ТИТАНИК 2</h1>
                        <p class="mb-0" style="font-family: Georgia, serif; font-size: 0.75rem; color: #fcd34d; font-style: italic;">Плавание сквозь Время</p>
                    </div>
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="{{ route('home') }}" class="nav-link px-3" style="color: #fbbf24;">Главная</a></li>
                <li><a href="{{ route('shop') }}" class="nav-link px-3" style="color: #fbbf24;">Рейсы</a></li>
{{--                <li><a href="{{ route('about') }}" class="nav-link px-3" style="color: #fbbf24;">О нас</a></li>--}}
{{--                <li><a href="{{ route('contacts') }}" class="nav-link px-3" style="color: #fbbf24;">Контакты</a></li>--}}
            </ul>

            <div class="col-md-3 text-end pe-3">
                <a href="{{ route('login') }}" class="btn btn-login me-2">Войти</a>
                <a href="{{ route('register') }}" class="btn btn-signup">Регистрация</a>
            </div>
        </header>
    @endif

    <main style="background: #0f172a;">
        @yield('main_content')
    </main>

    <!-- Подвал -->
    <footer class="footer-section py-5" style="background: linear-gradient(135deg, #0f172a, #1e293b, #334155); border-top: 2px solid #fbbf24;">
        <div class="container">
            <div class="row g-4">

                <!-- Колонка 1: О компании -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <h4 style="color: #fbbf24; font-family: Georgia, serif;">ТИТАНИК 2</h4>
                    </div>
                    <p style="color: #fcd34d; line-height: 1.6;">
                        Легенда возвращается в будущее. Самый роскошный круизный лайнер 21 века,
                        сочетающий историческое наследие с современными технологиями.
                    </p>
                    <div class="social-links mt-4">
                        <a href="#" class="text-decoration-none me-3" style="color: #fcd34d; font-size: 1.5rem;">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="#" class="text-decoration-none me-3" style="color: #fcd34d; font-size: 1.5rem;">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="text-decoration-none me-3" style="color: #fcd34d; font-size: 1.5rem;">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="text-decoration-none" style="color: #fcd34d; font-size: 1.5rem;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Колонка 2: Навигация -->
                <div class="col-lg-2 col-md-6">
                    <h5 style="color: #fbbf24; margin-bottom: 1rem;">Навигация</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-decoration-none" style="color: #fcd34d;">Главная</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('shop') }}" class="text-decoration-none" style="color: #fcd34d;">Рейсы</a>
                        </li>
                        @auth
                            <li class="mb-2">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: #fcd34d;">Личный кабинет</a>
                            </li>
                        @else
{{--                            <li class="mb-2">--}}
{{--                                <a href="{{ route('about') }}" class="text-decoration-none" style="color: #fcd34d;">О нас</a>--}}
{{--                            </li>--}}
{{--                            <li class="mb-2">--}}
{{--                                <a href="{{ route('contacts') }}" class="text-decoration-none" style="color: #fcd34d;">Контакты</a>--}}
{{--                            </li>--}}
                        @endauth
                    </ul>
                </div>

                <!-- Колонка 3: Услуги -->
                <div class="col-lg-3 col-md-6">
                    <h5 style="color: #fbbf24; margin-bottom: 1rem;">Услуги</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none" style="color: #fcd34d;">Каюты и номера</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none" style="color: #fcd34d;">Рестораны и бары</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none" style="color: #fcd34d;">Развлечения</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none" style="color: #fcd34d;">Спа и оздоровление</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none" style="color: #fcd34d;">Экскурсии</a>
                        </li>
                    </ul>
                </div>

                <!-- Колонка 4: Контакты -->
                <div class="col-lg-3 col-md-6">
                    <h5 style="color: #fbbf24; margin-bottom: 1rem;">Контакты</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div style="color: #fcd34d;">
                                <i class="fas fa-phone me-2"></i>
                                <span>+7 (800) 555-35-35</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div style="color: #fcd34d;">
                                <i class="fas fa-envelope me-2"></i>
                                <span>booking@titanic2.ru</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div style="color: #fcd34d;">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>г. Москва, Порт Сочи</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div style="color: #fcd34d;">
                                <i class="fas fa-clock me-2"></i>
                                <span>Пн-Вс: 9:00-21:00</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Нижняя часть подвала -->
            <div class="row mt-5 pt-4 border-top" style="border-color: #334155 !important;">
                <div class="col-md-6">
                    <p class="mb-0" style="color: #94a3b8;">
                        &copy; 2026 Титаник 2. Все права защищены.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#" class="text-decoration-none me-3" style="color: #94a3b8; font-size: 0.9rem;">Политика конфиденциальности</a>
                        <a href="#" class="text-decoration-none me-3" style="color: #94a3b8; font-size: 0.9rem;">Условия использования</a>
                        <a href="#" class="text-decoration-none" style="color: #94a3b8; font-size: 0.9rem;">Карта сайта</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
