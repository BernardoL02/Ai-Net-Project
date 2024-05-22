<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;

// REPLACE THIS
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

//WITH THIS
Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::get('/profile/update', [ProfileController::class, 'edit'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
});

require __DIR__ . '/auth.php';

Route::resource("genres", GenreController::class);
Route::resource('movies', MovieController::class);
