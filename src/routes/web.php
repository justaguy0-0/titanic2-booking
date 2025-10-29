<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// === ГЛАВНАЯ СТРАНИЦА — МАГАЗИН БИЛЕТОВ ===
Route::get('/', [ShopController::class, 'index'])->name('home');

// === ПУБЛИЧНЫЕ МАРШРУТЫ ===
Route::get('/voyage', function () {
    return view('voyage');
});

// === ТОЛЬКО АВТОРИЗОВАННЫЕ ===
Route::middleware('auth')->group(function () {

    // Магазин — выбор билетов и покупка
    Route::get('/shop/voyage/{voyage}', [ShopController::class, 'showVoyage'])->name('shop.voyage');
    Route::post('/shop/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Заказы
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.orders.show');
    Route::patch('/profile/orders/{order}/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');

    // Дашборд
    Route::get('/dashboard', fn() => view('dashboard'))
        ->name('dashboard');
});

// === АДМИНКА ===
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('place-departures', \App\Http\Controllers\Admin\PlaceDepartureController::class);
        Route::resource('iceberg-arrivals', \App\Http\Controllers\Admin\IcebergArrivalController::class);
        Route::resource('voyages', \App\Http\Controllers\Admin\VoyageController::class);
        Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::resource('entertainments', \App\Http\Controllers\Admin\EntertainmentController::class);
        Route::resource('dashboard', \App\Http\Controllers\Admin\DashboardController::class);
    });

// === АУТЕНТИФИКАЦИЯ ===
require __DIR__.'/auth.php';