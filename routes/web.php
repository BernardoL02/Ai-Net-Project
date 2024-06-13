<?php

use App\Models\Screening;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ScreeningController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


//WITH THIS
Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('profile/{user}/photo', [ProfileController::class, 'destroyPhoto']) ->name('profile.photo.destroy');

    //Adicionar aqui as rotas que só podem ser acedidas pelo ADMIN
    Route::middleware('can:type')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::resource('theaters',TheaterController::class);
            Route::delete('theater/{theater}/photo', [TheaterController::class, 'destroyPhoto'])->name('theater.photo.destroy')->can('update', 'theater');

        });

        Route::get('dashboard', function () {
            return view('dashboard.index');
        })->name('dashboard');

    });
});

Route::resource("genres", GenreController::class);
Route::resource('movies', MovieController::class);


Route::get('screening/{screening}/showcase', [ScreeningController::class,'showcase'])->name('screenings.showcase');

Route::post('cart/{screening}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');

Route::get('/search', [MovieController::class, 'index'])->name('movies.index');
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::resource('/purchases', PurchaseController::class);

//Create a receipt

Route::get('/receipt/{purchase}', [PDFController::class, 'generateReceipt'])->name('receipt.generatePDF');
Route::get('/purchases/{purchase}/receipt', [PDFController::class, 'showReceipt'])->name('receipt.show');
Route::get('/purchases/{purchase}/receipt/download', [PDFController::class, 'downloadReceipt'])->name('receipt.download');

//Tickets
Route::get('/purchases/{purchase}/tickets/download', [PurchaseController::class, 'downloadTickets'])->name('tickets.download');
Route::get('/purchases/{purchase}/tickets', [PurchaseController::class, 'showTickets'])->name('tickets.show');


require __DIR__ . '/auth.php';
