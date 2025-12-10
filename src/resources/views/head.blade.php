<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="@if(request()->is('login') || request()->is('register') || request()->is('password.request*')) auth-bg @endif">

    @if (Auth::check() || (!request()->routeIs('login') && !request()->routeIs('register') && !request()->is('password.request*')))
        <header class="site-header d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
            <div class="col-md-3 mb-2 mb-md-0 ps-3">
                <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                    <div class="ms-2">
                        <h1 class="site-logo-title">ТИТАНИК 2</h1>
                        <p class="site-logo-subtitle">Плавание сквозь Время</p>
                    </div>
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="{{ route('home') }}" class="nav-link px-3">Главная</a></li>
                <li><a href="{{ route('about') }}" class="nav-link px-3">О нас</a></li> 
                <li><a href="{{ route('voyage') }}" class="nav-link px-3">Рейсы</a></li>
                <li><a href="{{ route('shop') }}" class="nav-link px-3">Билеты</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3 d-flex align-items-center justify-content-end gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="user-name">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </a>

                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard.index') }}" class="btn btn-admin">
                            <i class="fas fa-crown me-1"></i>Админка
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-signup">Регистрация</a>
                @endauth
            </div>
        </header>
    @endif

    <main>
        @yield('main_content')
    </main>

    @if (Auth::check() || (!request()->routeIs('login') && !request()->routeIs('register') && !request()->is('password.request*')))
        <footer class="footer-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5 col-md-6">
                    <div class="footer-brand mb-3">
                        <h4 class="footer-brand-title">ТИТАНИК 2</h4>
                    </div>
                    <p class="footer-text">
                        Легенда возвращается в будущее. Самый роскошный круизный лайнер 21 века,
                        сочетающий историческое наследие с современными технологиями.
                    </p>
                 
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="row">
                        <div class="col-md-5">
                            <h5 class="footer-heading">Навигация</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="{{ route('home') }}" class="footer-link">Главная</a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('shop') }}" class="footer-link">Билеты</a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('voyage') }}" class="footer-link">Рейсы</a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('about') }}" class="footer-link">О нас</a>
                                </li>
                                @auth
                                    <li class="mb-2">
                                        <a href="{{ route('dashboard') }}" class="footer-link">Личный кабинет</a>
                                    </li>
                                @endauth
                            </ul>
                        </div>

                        <div class="col-md-7">
                            <h5 class="footer-heading">Контакты и поддержка</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="footer-contact-text">
                                        <i class="fas fa-phone me-2"></i>
                                        <span>+7 (800) 555-35-35</span>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="footer-contact-text">
                                        <i class="fas fa-envelope me-2"></i>
                                        <span>booking@titanic2.ru</span>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="footer-contact-text">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <span>г. Москва, Порт Сочи</span>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="footer-contact-text">
                                        <i class="fas fa-clock me-2"></i>
                                        <span>Пн-Вс: 9:00-21:00</span>
                                    </div>
                                </li>
                                @guest
                                    <li class="mb-2">
                                        <a href="{{ route('login') }}" class="footer-link">Войти в аккаунт</a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="{{ route('register') }}" class="footer-link">Регистрация</a>
                                    </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5 pt-4 border-top border-secondary">
                <div class="col-md-6">
                    <p class="footer-copyright mb-0">
                        &copy; 2026 Титаник 2. Все права защищены.
                    </p>
                </div>
                
            </div>
        </div>
    </footer>
    @endif
</body>
</html>