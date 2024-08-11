<?php

    use App\Http\Controllers\ContractController;
    use App\Http\Controllers\PackageController;
    use App\Http\Controllers\ProfileController;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;

    Route::get('/', function () {
        return Inertia::render('Auth/Login');
    })->name('login');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth'])->name('dashboard');


    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/index/{id}', [\App\Http\Controllers\ContractController::class, 'index'])->name('index');
        Route::get('/implementirano', [\App\Http\Controllers\ImplementationController::class, 'index'])->name('implementirano');
        Route::get('/search', [\App\Http\Controllers\ImplementationController::class, 'search'])->name('search');
        Route::get('/novi-klijent', [\App\Http\Controllers\ContractController::class, 'create'])->name('novi-klijent');
        Route::post('/novi-klijent', [\App\Http\Controllers\ContractController::class, 'store'])->name('novi-klijent');
        Route::get('/contract/{id}', [\App\Http\Controllers\ContractController::class, 'show'])->name('contract.show');
        Route::post('/contract/{contract}', [\App\Http\Controllers\ContractController::class, 'update'])->name('contract.update');
        Route::get('/filter/{filter}', [ContractController::class, 'filterContracts'])->name('filter');
        Route::get('/dodaj-korisnika', [\App\Http\Controllers\UserController::class, 'create'])->name('dodaj-korisnika');

        //Packages
        Route::get('/paket', [PackageController::class, 'create'])->name('paket.create');
        Route::post('/paket', [PackageController::class, 'store'])->name('paket.store');
        Route::patch('/paket/{package}', [PackageController::class, 'update'])->name('paket.update');

        Route::post('/dodaj-korisnika', [\App\Http\Controllers\UserController::class, 'store'])->name('dodaj-korisnika.store');

    });

    require __DIR__ . '/auth.php';
