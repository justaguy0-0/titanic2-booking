<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EntertainmentController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\VoyageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\CabinTypeController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\ProfileController;
use App\Models\Place;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/voyage', function () {
    return view('voyage');
});

Route::get('/test-places', function () {
    $places = Place::paginate(10);
    return view('admin.place.index', compact('places'));
});

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
});

require __DIR__.'/auth.php';
