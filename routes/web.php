<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/index/{id}', [\App\Http\Controllers\ContractController::class, 'index'])->name('index');
    Route::get('/implementirano', [\App\Http\Controllers\ImplementationController::class, 'index'])->name('implementirano');
    Route::get('/novi-klijent', [\App\Http\Controllers\ContractController::class, 'create'])->name('novi-klijent');
    Route::post('/novi-klijent', [\App\Http\Controllers\ContractController::class, 'store'])->name('novi-klijent');
});

require __DIR__.'/auth.php';
