<?php

use App\Models\Screening;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScreeningController;

// REPLACE THIS
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

//WITH THIS
Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('profile/{user}/photo', [ProfileController::class, 'destroyPhoto']) ->name('profile.photo.destroy');
});

require __DIR__ . '/auth.php';

Route::resource("genres", GenreController::class);

Route::resource('movies', MovieController::class);
Route::get('/search', [MovieController::class, 'index'])->name('movies.index');
Route::resource('theaters',TheaterController::class);
Route::delete('theater/{theater}/photo', [TheaterController::class, 'destroyPhoto'])->name('theater.photo.destroy')->can('update', 'theater');
Route::get('screening/{screening}/showcase', [ScreeningController::class,'showcase'])->name('screenings.showcase');


    // Add a discipline to the cart:
    Route::post('cart/{screening}', [CartController::class, 'addToCart'])
        ->name('cart.add');
    // Remove a discipline from the cart:
    Route::delete('cart/{id}', [CartController::class, 'removeFromCart'])
        ->name('cart.remove');
    // Show the cart:
    Route::get('cart', [CartController::class, 'show'])->name('cart.show');
    // Clear the cart:
    Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('cart', [CartController::class, 'confirm'])
    ->name('cart.confirm')
    ->can('confirm-cart');

