<?php

use App\Http\Controllers\PrinterController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [PrinterController::class, 'index']);


    //prevent manual accesing non get routes

    Route::get('/printers/create', [PrinterController::class, 'create']);
    Route::post('/printers', [PrinterController::class, 'store']);
    Route::get('/all', [PrinterController::class, 'all']);
    Route::get('/printers/{printer}', [PrinterController::class, 'show'])->name('printers.show');
    Route::get('/printers/{printer}/edit', [PrinterController::class, 'edit']);
    Route::patch('/printers/{printer}', [PrinterController::class, 'update']);
    Route::delete('/printers/{printer}', [PrinterController::class, 'destroy']);

    Route::get('/search', SearchController::class);
    Route::get('/tags/{tag:name}', TagController::class);
});

Route::middleware('guest')->group(function () {
    Route::get('/logout', function () {
        return redirect('/login');
    });

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

Route::delete('/logout', [SessionController::class, 'destroy']);


