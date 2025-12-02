<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EntertainmentController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\VoyageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\CabinTypeController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/', [HomeController::class, 'index'])->name('home');



Route::get('/voyage', function () {
    return view('voyage');
})->name('voyage');


Route::get('/shop', [ShopController::class, 'index'])->name('shop');


Route::middleware('auth')->group(function () {

    Route::get('/shop/select-tickets/{voyage}', [ShopController::class, 'showVoyage'])->name('shop.select-tickets');
    Route::post('/shop/purchase', [ShopController::class, 'purchase'])->name('shop.purchase');
    Route::get('/shop/payment', [ShopController::class, 'showPayment'])->name('shop.payment');
    Route::post('/shop/process-payment', [ShopController::class, 'processPayment'])->name('shop.process-payment');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.orders.show');
    Route::patch('/profile/orders/{order}/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');

   
    Route::get('/dashboard', fn() => view('dashboard'))
        ->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('places', PlaceController::class);
    Route::resource('voyages', VoyageController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('entertainments', EntertainmentController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('cabin-types', CabinTypeController::class);
    Route::resource('order-items', OrderItemController::class);
    Route::resource('payments', PaymentController::class);
});


require __DIR__.'/auth.php';