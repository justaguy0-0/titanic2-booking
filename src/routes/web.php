<?php

use App\Http\Controllers\Admin\PlaceDepartureController;
use App\Http\Controllers\ProfileController;
use App\Models\PlaceDeparture;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/test-places', function () {
    $places = PlaceDeparture::paginate(10);
    return view('admin.place-departures.index', compact('places'));
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
    Route::resource('place-departures', PlaceDepartureController::class);
});

require __DIR__.'/auth.php';
