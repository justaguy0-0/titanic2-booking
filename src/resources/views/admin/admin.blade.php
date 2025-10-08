<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Дополнительные стили --}}
    @stack('styles')
</head>
<body class="bg-gray-100">
    {{-- Навигационная панель --}}
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                {{-- Логотип и название --}}
                <div class="flex items-center">
                    <a href="{{ route('admin.place-departures.index') }}" class="text-xl font-bold text-gray-800">
                        🚀 Админ-панель
                    </a>
                </div>

                {{-- Меню навигации --}}
                <div class="flex space-x-4">
                    <a href="{{ route('admin.place-departures.index') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Места отправления
                    </a>
                    <a href="#" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Пользователи
                    </a>
                    <a href="#" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Настройки
                    </a>
                    
                    {{-- Выход --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 px-3 py-2 rounded-md text-sm font-medium">
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Основной контент --}}
    <main class="min-h-screen py-6">
        @yield('content')
    </main>

    {{-- Футер --}}
    <footer class="bg-white shadow-lg mt-12">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <p class="text-center text-gray-500 text-sm">
                © {{ date('Y') }} Админ-панель. Все права защищены.
            </p>
        </div>
    </footer>

    {{-- Дополнительные скрипты --}}
    @stack('scripts')
</body>
</html>