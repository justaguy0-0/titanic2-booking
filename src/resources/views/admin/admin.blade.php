<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель')</title>

    @vite(['resources/css/admin.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<div class="flex flex-1">

    <aside class="w-64 bg-white shadow-md flex-shrink-0">
        <div class="p-4 border-b">
            <h1 class="text-xl font-bold text-gray-800">Админ-панель</h1>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard.index') }}"
               class="flex items-center px-3 py-2 rounded-md hover:bg-blue-50 text-gray-800 font-medium">
                <i class="fas fa-chart-bar mr-2"></i> Главная
            </a>

            <div>
                <div class="text-gray-500 text-xs uppercase mt-4 mb-1 tracking-wider">Справочники</div>
                <a href="{{ route('admin.voyages.index') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-route mr-2"></i> Рейсы
                </a>
                <a href="{{ route('admin.places.index') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-location-dot mr-2"></i> Места
                </a>
                <a href="{{ route('admin.cabin-types.index') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-house mr-2"></i> Типы кают
                </a>
            </div>

            {{-- <div>
                <div class="text-gray-500 text-xs uppercase mt-4 mb-1 tracking-wider">Пользователи</div>
                <a class="flex items-center text-gray-400 px-3 py-2 rounded-md pointer-events-none opacity-50 cursor-not-allowed">
                    <i class="fas fa-users mr-2"></i> Все пользователи
                </a>
                <a class="flex items-center text-gray-400 px-3 py-2 rounded-md pointer-events-none opacity-50 cursor-not-allowed">
                    <i class="fas fa-user-cog mr-2"></i> Администраторы
                </a>
            </div> --}}

            <div>
                <div class="text-gray-500 text-xs uppercase mt-4 mb-1 tracking-wider">Продажи</div>
                <a href="{{ route('admin.tickets.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-ticket-alt mr-2"></i> Билеты
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-file-invoice mr-2"></i> Заказы
                </a>
                <a href="{{ route('admin.order-items.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-box mr-2"></i> Детали заказа
                </a>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-credit-card mr-2"></i> Платежи
                </a>
                <a href="{{ route('admin.entertainments.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-masks-theater mr-2"></i> Развлечения
                </a>
            </div>

            {{-- }}<div>
                <div class="text-gray-500 text-xs uppercase mt-4 mb-1 tracking-wider">Система</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-cog mr-2"></i> Настройки
                </a>
                <a href="{{ route('telescope') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <i class="fas fa-folder mr-2"></i> Логи
                </a>
            </div> --}}

            <div class="pt-4">
                <div class="text-gray-500 text-xs uppercase mb-2 tracking-wider">Пользователи</div>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">
                    <i class="fas fa-users mr-3"></i> Все пользователи
                </a>
                <a href="{{ route('admin.passengers.index') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">
                    <i class="fas fa-passport mr-3"></i> Пассажиры
                </a>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-4">
                <a href="{{ route('home') }}"
                   class="flex items-center px-3 py-2 rounded-md hover:bg-green-50 text-green-700 font-medium transition group">
                    <i class="fas fa-home mr-3 text-green-600 group-hover:text-green-700"></i>
                    На главную
                </a>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t mt-6">
                @csrf
                <button type="submit"
                        class="flex items-center w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-md font-medium">
                    <i class="fas fa-door-open mr-2"></i> Выйти
                </button>
            </form>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>

<footer class="bg-white shadow-inner">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <p class="text-center text-gray-500 text-sm">
            © {{ date('Y') }} Админ-панель. Все права защищены.
        </p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
