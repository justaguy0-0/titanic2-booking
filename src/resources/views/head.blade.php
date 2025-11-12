<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@if(request()->is('login') || request()->is('register') || request()->is('password.request*')) auth-bg @endif">

    {{-- Шапка для авторизованных пользователей --}}
    @if (Auth::check())
        <header class="titanic-header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid px-3 px-md-4">
                    <a href="/" class="navbar-brand d-flex align-items-center">
                        <div class="logo-wrapper">
                            <h1 class="logo-title mb-0">ТИТАНИК 2</h1>
                            <p class="logo-subtitle mb-0">Плавание сквозь Время</p>
                        </div>
                    </a>

                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarContent" aria-controls="navbarContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="#" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('shop') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Voyage</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Amenities</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Booking</a>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center flex-column flex-lg-row gap-2 mt-3 mt-lg-0">
                            <a href="{{ route('dashboard') }}" class="profile-link">
                                {{ Auth::user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-logout">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

    {{-- Шапка для гостей --}}
    @elseif (!request()->routeIs('login') && !request()->routeIs('register') && !request()->is('password.request*'))
        <header class="titanic-header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid px-3 px-md-4">
                    <a href="/" class="navbar-brand d-flex align-items-center">
                        <div class="logo-wrapper">
                            <h1 class="logo-title mb-0">ТИТАНИК 2</h1>
                            <p class="logo-subtitle mb-0">Плавание сквозь Время</p>
                        </div>
                    </a>

                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarContent" aria-controls="navbarContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="#" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('shop') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Voyage</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Amenities</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Booking</a>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center flex-column flex-lg-row gap-2 mt-3 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-signup">Sign-up</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    @endif

    <main>
        @yield('main_content')
    </main>

    @yield('scripts')
</body>
</html>
