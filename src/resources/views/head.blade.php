<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ТИТАНИК 2')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="@if(request()->is('login') || request()->is('register') || request()->is('password.request*')) auth-bg @endif" style="background: #0f172a; margin: 0; padding: 0;"> {{-- ДОБАВЬТЕ ЭТО --}}

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
                <li><a href="{{ route('home') }}" class="nav-link px-3">Главная</a></li>
                <li><a href="{{ route('voyage') }}" class="nav-link px-3">Путешествие</a></li>
                <li><a href="#" class="nav-link px-3">Услуги</a></li>
                <li><a href="#" class="nav-link px-3">Бронирование</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3">
                <span class="me-3">
                    <a href="dashboard" class="profile-link">{{ Auth::user()->name }}</a>
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
                <li><a href="#" class="nav-link px-3">Главная</a></li>
                <li><a href="{{ route('shop') }}" class="nav-link px-3">Путешествие</a></li>
                <li><a href="#" class="nav-link px-3">Услуги</a></li>
                <li><a href="#" class="nav-link px-3">Бронирование</a></li>
            </ul>

            <div class="col-md-3 text-end pe-3">
                <a href="{{ route('login') }}" class="btn btn-login me-2">Войти</a>
                <a href="{{ route('register') }}" class="btn btn-signup">Регистрация</a>
            </div>
        </header>
    @endif

    <main style="background: #0f172a;"> {{-- ДОБАВЬТЕ ЭТО --}}
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
                        <a href="{{ route('voyage') }}" class="text-decoration-none" style="color: #fcd34d;">Бронирование</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('shop') }}" class="text-decoration-none" style="color: #fcd34d;">Рейсы</a>
                    </li>
                    @auth
                        <li class="mb-2">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: #fcd34d;">Личный кабинет</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('profile.orders') }}" class="text-decoration-none" style="color: #fcd34d;">Мои заказы</a>
                        </li>
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

    <style>
        /* Убираем все отступы */
        body, html {
            margin: 0;
            padding: 0;
            background: #0f172a; /* Темный фон для всего */
        }

        body.auth-bg {
            background: #1e293b;
            min-height: 100vh;
        }

        /* Стили подвала */
        .footer-section a:hover {
            color: #fbbf24 !important;
            text-decoration: underline !important;
        }
        
        .social-links a:hover {
            transform: scale(1.2);
            transition: transform 0.3s ease;
        }


        .nav-link {
            color: #fcd34d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: #fbbf24;
            transition: width 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fbbf24;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }

        .profile-link {
            color: #fcd34d;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.375rem 0.75rem;
            white-space: nowrap;
        }

        .profile-link:hover {
            color: #fbbf24;
        }

        .btn-login,
        .btn-logout {
            border: 2px solid #fbbf24;
            color: #fbbf24;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: transparent;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-login:hover,
        .btn-logout:hover {
            background-color: #fbbf24;
            color: #1e293b;
            border-color: #fbbf24;
        }

        .btn-signup {
            background-color: #fbbf24;
            color: #1e293b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid #fbbf24;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-signup:hover {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: #1e293b;
        }

        /* ========== ОБЩИЕ КОМПОНЕНТЫ ========== */

        /* Заголовки страниц */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 1.5rem;
        }

        /* Карточки секций */
        .section-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .section-header {
            background: linear-gradient(135deg, #1e293b, #334155);
            border-bottom: 2px solid #fbbf24;
            padding: 1.25rem 1.5rem;
        }

        .section-header h5 {
            color: #fbbf24;
            font-weight: 600;
            margin: 0;
        }

        .section-subtitle {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Кнопка назад */
        .btn-back {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border: 2px solid #fbbf24;
            color: #fbbf24;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #fbbf24;
            color: #1e293b;
        }

        /* Формы */
        .form-label {
            color: #fcd34d;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .custom-input,
        .form-control {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            color: #fcd34d !important;
            padding: 0.625rem 0.875rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .custom-input:focus,
        .form-control:focus {
            border-color: #fbbf24 !important;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1) !important;
            outline: none;
        }

        /* Чекбоксы */
        .form-check-input:checked {
            background-color: #fbbf24;
            border-color: #fbbf24;
        }

        .form-check-input:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 0.25rem rgba(251, 191, 36, 0.25);
        }

        /* Кнопки действий */
        .btn-submit {
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            border: none;
            color: #1e293b;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
            color: #1e293b;
        }

        .btn-submit:disabled {
            background: #334155;
            color: #64748b;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-danger {
            background: #dc2626;
            border: none;
            color: white;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #334155;
            border: none;
            color: #fcd34d;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.375rem;
        }

        /* Сообщения */
        .empty-message {
            text-align: center;
            padding: 2rem 0;
            color: #94a3b8;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.875rem;
        }

        .selection-note {
            margin-top: 1rem;
            color: #ef4444;
            font-size: 0.875rem;
            text-align: center;
        }

        /* Билеты */
        .tickets-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ticket-item {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .ticket-item:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25);
            transform: translateY(-2px);
        }

        .ticket-label {
            font-weight: 500;
            color: #fcd34d;
            cursor: pointer;
        }

        .ticket-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fbbf24;
        }

        .place-number {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        /* Развлечения */
        .entertainments-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .entertainment-item {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .entertainment-info {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            gap: 1rem;
        }

        .entertainment-name {
            font-weight: 600;
            color: #fcd34d;
            margin-bottom: 0.25rem;
        }

        .entertainment-desc {
            color: #94a3b8;
        }

        .entertainment-price {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fbbf24;
            white-space: nowrap;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-label {
            font-size: 0.875rem;
            color: #94a3b8;
            margin: 0;
        }

        .quantity-input {
            width: 80px;
            padding: 0.375rem 0.75rem;
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            border-radius: 0.375rem;
            color: #fcd34d !important;
            font-size: 0.875rem;
            text-align: center;
        }

        /* Итоговая карточка */
        .total-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .total-label {
            font-weight: 700;
            color: #fbbf24;
            margin: 0;
        }

        .total-amount {
            font-weight: 700;
            color: #fbbf24;
            margin: 0;
        }

        /* Информация о рейсе */
        .voyage-info-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .voyage-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fbbf24;
        }

        .voyage-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: #fcd34d;
        }

        .detail-item strong {
            font-weight: 600;
        }

        .place-name {
            margin-left: 0.25rem;
            color: #fbbf24;
        }

        /* Таблицы */
        .orders-table {
            width: 100%;
            color: #fcd34d;
        }

        .orders-table thead {
            background: linear-gradient(135deg, #1e293b, #334155);
            border-bottom: 2px solid #fbbf24;
        }

        .orders-table th {
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fbbf24;
        }

        .orders-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #334155;
        }

        .orders-table tbody tr:hover {
            background: #0f172a;
        }

        /* Статус бейджи */
        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Ссылки действий */
        .action-link {
            color: #fbbf24;
            text-decoration: none;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .action-link:hover {
            color: #f59e0b;
        }

        /* Модальные окна */
        .custom-modal .modal-content {
            background: #1e293b;
            border: 1px solid #334155;
            color: #fcd34d;
        }

        .custom-modal .modal-header {
            border-bottom: 1px solid #334155;
        }

        .custom-modal .modal-title {
            color: #fbbf24;
        }

        .custom-modal .modal-footer {
            border-top: 1px solid #334155;
        }

        /* ========== АДАПТИВНОСТЬ ========== */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: linear-gradient(180deg, #334155 0%, #1e293b 100%);
                padding: 1rem;
                margin-top: 1rem;
                border-radius: 0.5rem;
                border: 1px solid rgba(251, 191, 36, 0.2);
            }

            .nav-link {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(251, 191, 36, 0.1);
            }

            .nav-link:last-child {
                border-bottom: none;
            }

            .nav-link::after {
                display: none;
            }

            .nav-link:hover,
            .nav-link.active {
                background-color: rgba(251, 191, 36, 0.1);
                border-radius: 0.25rem;
            }

            .voyage-details {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        @media (max-width: 575.98px) {
            .logo-title {
                font-size: 1.1rem;
                letter-spacing: 1px;
            }

            .logo-subtitle {
                font-size: 0.6rem;
            }

            .btn-login,
            .btn-logout,
            .btn-signup,
            .btn-back {
                width: 100%;
                text-align: center;
            }

            .profile-link {
                width: 100%;
                text-align: center;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .section-header {
                padding: 1rem;
            }

            .ticket-item,
            .entertainment-item {
                padding: 0.875rem;
            }

            .entertainment-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .orders-table th,
            .orders-table td {
                padding: 0.75rem;
                font-size: 0.875rem;
            }
        }

    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>