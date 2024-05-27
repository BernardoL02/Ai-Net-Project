<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\UserController;

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
