<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="@if(request()->is('login') || request()->is('register') || request()->is('password.request*')) auth-bg @endif">

    {{-- Шапка для авторизованных пользователей --}}
    @if (Auth::check())
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4"
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
                <li><a href="#" class="nav-link px-3">Home</a></li>
                <li><a href="#" class="nav-link px-3">Flights</a></li>
                <li><a href="#" class="nav-link px-3">Amenities</a></li>
                <li><a href="#" class="nav-link px-3">Booking</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3">
                <span class="me-3" style="color: #fcd34d;">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">Logout</button>
                </form>
            </div>
        </header>

    {{-- Шапка для гостей (только на публичных страницах, кроме login/register/password) --}}
    @elseif (!request()->routeIs('login') && !request()->routeIs('register') && !request()->is('password.request*'))
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4"
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
                <li><a href="#" class="nav-link px-3">Home</a></li>
                <li><a href="#" class="nav-link px-3">Flights</a></li>
                <li><a href="#" class="nav-link px-3">Amenities</a></li>
                <li><a href="#" class="nav-link px-3">Booking</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3">
                <a href="{{ route('login') }}" class="btn btn-login me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-signup">Sign-up</a>
            </div>
        </header>
    @endif

    <main>
        @yield('main_content')
    </main>

    <style>
        /* Навигация */
        header .nav-link {
            color: #fcd34d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        header .nav-link:hover {
            color: #fbbf24 !important;
            border-bottom: 2px solid #fbbf24;
            padding-bottom: 0.25rem;
        }

        /* Кнопки */
        .btn-login {
            border: 2px solid #fbbf24;
            color: #fbbf24;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #a6801f;
            color: #1e293b;
        }

        .btn-signup {
            background-color: #fbbf24;
            color: #1e293b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-signup:hover {
            background-color: #f59e0b;
        }

        .btn-logout {
            border: 2px solid #fbbf24;
            color: #fbbf24;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: none;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #a6801f;
            color: #1e293b;
        }

        /* Фон для страниц login/register/password */
        body.auth-bg {
            background: linear-gradient(135deg, #0c2d48 0%, #1a365d 50%, #2d3748 100%);
            min-height: 100vh;
        }

        /* Отступ для контента */
        main {
            padding-top: 1rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>