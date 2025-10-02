<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    @if (Auth::check())
        <!-- Шапка для авторизованных: с именем и logout -->
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4" style="background: linear-gradient(90deg, #1e293b 0%, #334155 50%, #1e293b 100%); border-bottom: 2px solid #fbbf24;">
            <div class="col-md-3 mb-2 mb-md-0 ps-3">
                <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                    <div class="ms-2">
                        <h1 class="mb-0" style="font-family: Georgia, serif; font-size: 1.5rem; color: #fbbf24; letter-spacing: 2px; font-weight: bold;">ТИТАНИК 2</h1>
                        <p class="mb-0" style="font-family: Georgia, serif; font-size: 0.75rem; color: #fcd34d; font-style: italic;">Плавание сквозь Время</p>
                    </div>
                </a>
            </div>
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Home</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Flights</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Amenities</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Booking</a></li>
            </ul>
            <div class="col-md-3 text-end pe-3">
                <span class="me-3" style="color: #fcd34d;">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn" style="border: 2px solid #fbbf24; color: #fbbf24; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Logout</button>
                </form>
            </div>
        </header>
    @elseif (!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.request'))
        <!-- Шапка для гостей: только на НЕ-auth страницах (главная и другие публичные) -->
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4" style="background: linear-gradient(90deg, #1e293b 0%, #334155 50%, #1e293b 100%); border-bottom: 2px solid #fbbf24;">
            <div class="col-md-3 mb-2 mb-md-0 ps-3">
                <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                    <div class="ms-2">
                        <h1 class="mb-0" style="font-family: Georgia, serif; font-size: 1.5rem; color: #fbbf24; letter-spacing: 2px; font-weight: bold;">ТИТАНИК 2</h1>
                        <p class="mb-0" style="font-family: Georgia, serif; font-size: 0.75rem; color: #fcd34d; font-style: italic;">Плавание сквозь Время</p>
                    </div>
                </a>
            </div>
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Home</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Flights</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Amenities</a></li>
                <li><a href="#" class="nav-link px-3" style="color: #fcd34d; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.875rem;">Booking</a></li>
            </ul>
            <div class="col-md-3 text-end pe-3">
                <a href="{{ route('login') }}" class="btn me-2" style="border: 2px solid #fbbf24; color: #fbbf24; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;">Login</a>
                <a href="{{ route('register') }}" class="btn" style="background-color: #fbbf24; color: #1e293b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border: none; text-decoration: none;">Sign-up</a>
            </div>
        </header>
    @endif

    <style>
        header .nav-link:hover {
            color: #fbbf24 !important;
            border-bottom: 2px solid #fbbf24;
            padding-bottom: 0.25rem;
            transition: all 0.3s ease;
        }
        header button:first-of-type:hover, /* Для logout */
        header a.btn:first-of-type:hover { /* Для login */
            background-color: #fbbf24;
            color: #1e293b;
            transition: all 0.3s ease;
        }
        header button:last-of-type:hover, /* Если будет */
        header a.btn:last-of-type:hover { /* Для sign-up */
            background-color: #f59e0b;
            transition: all 0.3s ease;
        }
    </style>

    @yield('main_content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>