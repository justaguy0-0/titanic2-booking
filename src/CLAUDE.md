# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Общие сведения о проекте

Это веб-приложение **Titanic 2 Booking** на Laravel 12 для бронирования билетов на круизы. Проект использует:
- PHP 8.2+ с Laravel 12
- Vite для сборки фронтенд-ресурсов
- Tailwind CSS и Bootstrap для стилизации
- Spatie Laravel Permission для управления ролями (админ/пользователь)
- Alpine.js для интерактивности
- PostgreSQL/MySQL база данных

## Команды для разработки

### Запуск приложения
```bash
# Запуск dev-окружения (все сервисы одновременно: server, queue, logs, vite)
composer dev

# Альтернатива - запуск компонентов по отдельности:
php artisan serve        # Локальный сервер на http://127.0.0.1:8000
npm run dev             # Vite dev server на http://127.0.0.1:5173
php artisan queue:listen # Очередь задач
php artisan pail        # Просмотр логов
```

### Сборка и тестирование
```bash
npm run build          # Production сборка фронтенд-ресурсов
composer test          # Запуск PHPUnit тестов
php artisan test       # Альтернативный способ запуска тестов
```

### Работа с базой данных
```bash
php artisan migrate           # Применить миграции
php artisan migrate:fresh     # Пересоздать БД
php artisan db:seed           # Заполнить тестовыми данными
php artisan migrate:fresh --seed  # Пересоздать БД и заполнить данными
```

### Другие полезные команды
```bash
php artisan config:clear    # Очистить кеш конфигурации
php artisan cache:clear     # Очистить кеш приложения
php artisan route:list      # Список всех маршрутов
php artisan tinker          # REPL для Laravel
composer dump-autoload     # Обновить autoload
```

## Архитектура приложения

### Основная бизнес-логика

**Модели предметной области:**
- `User` - пользователи с ролями (admin/обычный)
- `Voyage` - круизные рейсы
- `Place` - города отправления/прибытия
- `Ticket` - билеты на рейсы
- `CabinType` - типы кают
- `Order` - заказы пользователей
- `OrderItem` - позиции в заказе (билеты/развлечения)
- `Payment` - платежи по заказам
- `Entertainment` - развлечения на борту

**Важные связи:**
- Order имеет метод `refreshTotalPrice()` для автоматического пересчета итоговой стоимости на основе OrderItems
- Используется Spatie Permission: проверяйте роли через `$user->hasRole('admin')`

### Структура маршрутов (routes/web.php)

1. **Публичные маршруты:**
   - `/` - главная страница
   - `/voyage` - информация о круизах
   - `/shop` - каталог доступных рейсов

2. **Защищенные маршруты (middleware: auth):**
   - `/shop/voyage/{voyage}` - просмотр и бронирование конкретного рейса
   - `/shop/purchase` - оформление покупки
   - `/profile/*` - профиль пользователя, заказы, отмена заказов
   - `/dashboard` - личный кабинет

3. **Административная панель (middleware: auth, admin):**
   - Префикс `/admin`, неймспейс `admin.*`
   - CRUD-ресурсы: places, voyages, tickets, orders, entertainments, cabin-types, order-items, payments
   - Все контроллеры в `App\Http\Controllers\Admin\`

### Фронтенд-структура

**Ресурсы собираются Vite:**
- `resources/css/app.css` - основные стили
- `resources/css/admin.css` - стили админ-панели
- `resources/js/app.js` - JS точка входа

**Views (Blade-шаблоны):**
- `resources/views/layouts/` - базовые макеты
- `resources/views/admin/` - административные представления
- `resources/views/shop/` - магазин/каталог
- `resources/views/profile/` - профиль пользователя
- `resources/views/auth/` - аутентификация (Laravel Breeze)

**Используемые CSS-фреймворки:**
- Tailwind CSS (основной) - настроен через `tailwind.config.js`
- Bootstrap 5.3.8 - для некоторых компонентов
- Font Awesome 7.1 - иконки

### Middleware

- `AdminMiddleware` - проверяет роль 'admin' через `$user->hasRole('admin')`, редиректит на `/` с сообщением "Доступ запрещён" при отказе

## Особенности разработки

### При работе с моделями:
- Используйте Eloquent relationships, они уже настроены
- При изменении OrderItem вызывайте `$order->refreshTotalPrice()` для обновления суммы
- Для проверки прав используйте Spatie Permission: `hasRole()`, `can()`, `hasPermissionTo()`

### При работе с фронтендом:
- Vite настроен на `127.0.0.1:5173`
- В Blade используйте директиву `@vite()` для подключения ресурсов
- При изменении Tailwind-классов убедитесь, что они присутствуют в `tailwind.config.js`

### При работе с маршрутами:
- Публичные маршруты не требуют аутентификации
- Маршруты магазина требуют `auth`
- Административные маршруты требуют `auth` + `admin` роль
- Используйте именованные маршруты (`route('name')`) вместо хардкод-путей

### База данных:
- Миграции находятся в `database/migrations/`
- Seeders в `database/seeders/`
- Factories в `database/factories/`
- При создании новых таблиц используйте миграции, не изменяйте БД вручную
