# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Общие сведения о проекте

Это веб-приложение **Titanic 2 Booking** на Laravel 12 для бронирования билетов на круизы. Проект использует:
- **PHP 8.2+** с **Laravel 12**
- **Vite 7.0.4** для сборки фронтенд-ресурсов
- **Tailwind CSS 3.1** (основной) и **Bootstrap 5.3.8** для стилизации
- **Spatie Laravel Permission 6.21** для управления ролями (admin/user)
- **Alpine.js 3.4.2** для интерактивности
- **PostgreSQL/MySQL** база данных
- **Laravel Breeze 2.3** для аутентификации
- **Laravel Telescope 5.14** для отладки (dev)

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
- `Order` имеет метод `refreshTotalPrice()` для автоматического пересчета итоговой стоимости на основе OrderItems
- `OrderItem` использует полиморфизм: `item_type` может быть 'ticket' или 'entertainment', accessor `item` возвращает связанную модель
- `Voyage` связан с `Place` дважды (departurePlace и arrivalPlace)
- `Ticket` имеет статусы: 'Доступно' или 'Забронирован'
- Используется Spatie Permission: проверяйте роли через `$user->hasRole('admin')`

**Статусы:**
- **Ticket.status:** 'Доступно', 'Забронирован'
- **Order.status:** 'Оплачен', 'Новый', 'Обработан', 'Отменён'

### Структура маршрутов (routes/web.php)

1. **Публичные маршруты:**
   - `/` - главная страница
   - `/voyage` - информация о круизах
   - `/shop` - каталог доступных рейсов

2. **Защищенные маршруты (middleware: auth):**
   - `/shop/select-tickets/{voyage}` - просмотр и выбор билетов для конкретного рейса
   - `/shop/purchase` - валидация и сохранение выбора в сессию
   - `/shop/payment` - страница оплаты с итоговой суммой
   - `/shop/process-payment` - обработка платежа и создание заказа
   - `/profile/*` - профиль пользователя, заказы, отмена заказов
   - `/dashboard` - личный кабинет

3. **Административная панель (middleware: auth, admin):**
   - Префикс `/admin`, неймспейс `admin.*`
   - CRUD-ресурсы: places, voyages, tickets, orders, entertainments, cabin-types, order-items, payments
   - Все контроллеры в `App\Http\Controllers\Admin\`

### Фронтенд-структура

**Ресурсы собираются Vite:**
- `resources/css/app.css` - основные стили (импортирует все user/*.css)
- `resources/css/admin.css` - стили админ-панели (импортирует admin/head.css)
- `resources/js/app.js` - JS точка входа (Alpine.js + Bootstrap)

**CSS организация (модульная):**
- `resources/css/user/` - стили по функционалу:
  - `head.css` - навигация, общие элементы
  - `home.css` - главная страница
  - `voyage.css` - информация о круизах
  - `shop.css` - каталог рейсов
  - `select-tickets.css` - выбор билетов
  - `payment.css` - страница оплаты
  - `dashboard.css` - личный кабинет

**Views (Blade-шаблоны):**
- `resources/views/layouts/` - базовые макеты (app, guest)
- `resources/views/admin/` - административные представления (13 директорий с CRUD views)
- `resources/views/shop/` - магазин (select-tickets.blade.php, payment.blade.php)
- `resources/views/profile/` - профиль пользователя, заказы
- `resources/views/auth/` - аутентификация (Laravel Breeze)
- `resources/views/components/` - переиспользуемые Blade компоненты

**Используемые CSS-фреймворки:**
- **Tailwind CSS 3.1** (основной) - настроен через `tailwind.config.js`
- **Bootstrap 5.3.8** - для некоторых компонентов (alerts, modals, accordion)
- **Font Awesome 7.1** - иконки

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
- Миграции находятся в `database/migrations/` (15 миграций)
- Seeders в `database/seeders/` (9 сидеров)
- Factories в `database/factories/` (9 фабрик)
- При создании новых таблиц используйте миграции, не изменяйте БД вручную

## Процесс бронирования билетов (ключевой flow)

Основной процесс бронирования в **ShopController**:

1. **showVoyage($voyageId)** - Показать билеты рейса
   - Загружает рейс с местами отправления/прибытия
   - Фильтрует билеты по статусу 'Доступно'
   - Загружает все развлечения

2. **purchase(Request)** - Валидация и сохранение в сессию
   - Валидирует: `voyage_id`, `tickets[]`, `entertainments[]` (опционально)
   - Проверяет доступность выбранных билетов
   - Сохраняет `order_data` в сессию
   - Редирект на `/shop/payment`

3. **showPayment()** - Страница оплаты
   - Извлекает `order_data` из сессии
   - Повторно проверяет доступность билетов
   - Рассчитывает итоговую стоимость (билеты + развлечения * количество)
   - Передает данные в view для отображения

4. **processPayment(Request)** - Обработка платежа **[ВАЖНО!]**
   - Использует `DB::beginTransaction()` для атомарности
   - Применяет **пессимистическую блокировку** `lockForUpdate()` на билеты
   - **Симуляция платежа:** `rand(1, 100)` - если > 70, то платеж отклоняется (30% шанс)
   - При успехе (70% вероятность):
     - Создает `Order` со статусом **'Оплачен'**
     - Создает `OrderItem` для каждого билета (`item_type='ticket'`)
     - Создает `OrderItem` для каждого развлечения (`item_type='entertainment'`)
     - Обновляет статус билетов на **'Забронирован'**
     - `DB::commit()` - сохраняет изменения
   - Очищает сессию
   - Редирект на `profile.orders` с сообщением успеха/ошибки

**Ключевые особенности:**
- Session-based хранение данных заказа перед оплатой
- Пессимистические блокировки предотвращают race conditions
- Транзакции гарантируют целостность данных
- Повторная проверка доступности билетов на каждом шаге

## Важные технические детали

### Обязательные поля в fillable

**OrderItem** требует `item_type` в массиве `$fillable`:
```php
protected $fillable = [
    'order_id',
    'ticket_id',
    'entertainment_id',
    'type',
    'price',
    'quantity',
    'item_type',   // ОБЯЗАТЕЛЬНО! БД требует это поле
];
```

### Пересчет итоговой суммы заказа

При изменении `OrderItem` всегда вызывайте:
```php
$order->refreshTotalPrice();
```
Метод автоматически суммирует `price * quantity` всех позиций.

### Проверка ролей

Используйте Spatie Permission:
```php
// В контроллерах
if ($user->hasRole('admin')) { ... }

// В Blade
@role('admin')
    <!-- Админ-контент -->
@endrole
```

### Vite и CSS

При добавлении нового CSS файла:
1. Создайте файл в `resources/css/user/`
2. Импортируйте в `resources/css/app.css`:
   ```css
   @import 'user/your-file.css';
   ```
3. Vite автоматически подхватит изменения

### Bootstrap Accordion в развлечениях

На страницах `shop.blade.php` и `select-tickets.blade.php` используется Bootstrap accordion для группировки развлечений. Структура:
```blade
<div class="accordion" id="entertainmentsAccordion">
    @foreach($entertainments->chunk(ceil($entertainments->count() / 3)) as $index => $chunk)
        <div class="accordion-item">
            <button class="accordion-button" data-bs-target="#collapse{{ $index }}">
                Группа {{ $index + 1 }}
            </button>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse">
                <!-- Содержимое группы -->
            </div>
        </div>
    @endforeach
</div>
```

## Отладка и мониторинг

### Laravel Telescope
Визуальный отладчик доступен в dev-окружении:
- URL: `/telescope`
- Показывает запросы, queries, logs, exceptions
- Требует роль admin для доступа

### Laravel Pail
Real-time просмотр логов:
```bash
php artisan pail
# или с таймаутом
php artisan pail --timeout=0
```

### Tinker REPL
Интерактивная консоль Laravel:
```bash
php artisan tinker
# Примеры:
>>> User::count()
>>> Order::with('orderItems')->find(1)
>>> DB::table('tickets')->where('status', 'Доступно')->count()
```
