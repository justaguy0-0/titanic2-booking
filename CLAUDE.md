# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Titanic2 Booking is a Laravel 12 application for managing cruise ship voyage bookings. The system handles ticket sales, entertainment services, and user orders. Runs in Docker with MySQL, nginx, and phpMyAdmin.

## Development Commands

### Start Development Server
```bash
cd src && composer dev
```
This concurrently runs: Laravel server, queue listener, Pail logs, and Vite dev server.

### Docker Services
```bash
# Run artisan commands
docker compose run --rm artisan <command>

# Run composer
docker compose run --rm composer <command>

# Run npm
docker compose run --rm node npm <command>
```

### Database
```bash
# Run migrations
docker compose run --rm artisan migrate

# Seed database
docker compose run --rm artisan db:seed

# Fresh migration with seeds
docker compose run --rm artisan migrate:fresh --seed
```

### Testing
```bash
# Run all tests
cd src && composer test
# or
docker compose run --rm artisan test

# Run specific test
docker compose run --rm artisan test --filter=TestName
```

### Code Quality
```bash
# Format code with Laravel Pint
docker compose run --rm composer pint
```

## Architecture

### Domain Model

The booking system revolves around **Voyages** (cruise trips) that have **Tickets** (cabin reservations). Users create **Orders** containing **OrderItems** (tickets + optional entertainments).

**Core Entities:**

- **Voyage**: Cruise trip between two Places with departure/arrival dates
  - Relationships: `belongsTo` Place (departure/arrival), `hasMany` Ticket
  - Important: Uses `travel_time` (integer hours), `base_price` (decimal)

- **Ticket**: Individual cabin booking for a specific Voyage and CabinType
  - Foreign key: `voyages_id` (note: not `voyage_id`)
  - Status: "Доступно" (available) or "Забронирован" (reserved)
  - Each ticket has unique `number` per voyage

- **Order**: User's purchase container
  - Status: "Новый", "Подтвержден", "Отменен"
  - Method `refreshTotalPrice()` recalculates from OrderItems
  - Relationships: `belongsTo` User, `hasMany` OrderItem, `hasMany` Payment

- **OrderItem**: Polymorphic line item in order
  - `item_type`: 'ticket' or 'entertainment'
  - Links to either `ticket_id` or `entertainment_id` (one must be null)
  - Stores `price` and `quantity` at time of purchase

- **Entertainment**: Add-on services (meals, excursions)
  - Can be purchased multiple times per order

- **CabinType**: Cabin classes (Economy, Business, First Class)
  - Referenced by Ticket for pricing tiers

- **Place**: Geographic locations (ports)

- **User**: Laravel Breeze authentication + Spatie permissions
  - Role-based access control (admin role for admin panel)

### Application Flow

**Public Routes:**
- `/` - Home
- `/voyage` - Voyage info page
- `/shop` - Browse voyages and entertainments (ShopController@index)

**Authenticated Routes:**
- `/shop/voyage/{voyage}` - Select tickets for specific voyage (ShopController@showVoyage)
- `POST /shop/purchase` - Create order with tickets + entertainments (ShopController@purchase)
- `/profile/orders` - View user's orders (ProfileController@orders)
- `/profile/orders/{order}` - Order details (ProfileController@showOrder)
- `PATCH /profile/orders/{order}/cancel` - Cancel order (ProfileController@cancelOrder)

**Admin Routes** (`/admin/*` with admin middleware):
- Resource controllers for: places, voyages, tickets, orders, entertainments, cabin-types, order-items, payments
- Dashboard at `/admin/dashboard`

### Critical Business Logic

**ShopController@purchase** (src/app/Http/Controllers/ShopController.php:51-134):
- Uses DB transaction with row locking (`lockForUpdate()`) to prevent double-booking
- Validates ticket availability before creating order
- Creates Order → creates OrderItems for tickets → marks tickets as "Забронирован"
- Adds entertainment OrderItems if requested
- Calculates total_price from all items
- Returns to `/profile/orders` on success

**Order Cancellation Flow:**
- User cancels order via ProfileController@cancelOrder
- Order status → "Отменен"
- Associated tickets status → "Доступно" (released for rebooking)

### Database Conventions

Important naming: `tickets.voyages_id` (not `voyage_id`). This pattern is used throughout migrations and models.

Seeders run in order via DatabaseSeeder:
1. RoleSeeder (admin/user roles)
2. PlaceSeeder
3. CabinTypeSeeder
4. EntertainmentSeeder
5. VoyageSeeder
6. TicketSeeder
7. OrderSeeder
8. OrderItemSeeder
9. PaymentSeeder

### Technology Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Auth**: Laravel Breeze (Blade stack)
- **Permissions**: Spatie Laravel Permission
- **Database**: MySQL 8.0 (via Docker)
- **Frontend**: Blade templates, Tailwind CSS v3, Alpine.js
- **Build**: Vite
- **Dev Tools**: Laravel Telescope (debugging), Laravel Pail (logs)
- **Queue**: Database driver
- **Cache/Session**: Database driver

### Docker Environment

Access points:
- Application: http://localhost:8000
- phpMyAdmin: http://localhost:8080
- MySQL: localhost:3316 (external)

Database credentials in `src/.env`:
- Host: `mysql` (internal) or `localhost:3316` (external)
- Database: `titanic_db`
- User: `whiteowl`

All source in `src/` is mounted to `/var/www/laravel` in containers.

## Key Files

- `src/routes/web.php` - All route definitions
- `src/app/Http/Controllers/ShopController.php` - Booking transaction logic
- `src/app/Http/Controllers/ProfileController.php` - User order management
- `src/app/Http/Controllers/Admin/*` - Admin CRUD operations
- `src/database/seeders/DatabaseSeeder.php` - Seeder execution order
- `docker-compose.yaml` - Service definitions
