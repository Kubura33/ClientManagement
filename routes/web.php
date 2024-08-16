<?php

    use App\Http\Controllers\ContractController;
    use App\Http\Controllers\PackageController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
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
        Route::get('/implementirano/{market}', [\App\Http\Controllers\ImplementationController::class, 'index'])->name('implementirano');
        Route::get('/search', [\App\Http\Controllers\ImplementationController::class, 'search'])->name('search');
        Route::get('/novi-klijent/{id}', [\App\Http\Controllers\ContractController::class, 'create'])->name('novi-klijent.create');
        Route::post('/novi-klijent', [\App\Http\Controllers\ContractController::class, 'store'])->name('novi-klijent');
        Route::get('/contract/{id}', [\App\Http\Controllers\ContractController::class, 'show'])->name('contract.show');
        Route::post('/contract/{contract}', [\App\Http\Controllers\ContractController::class, 'update'])->name('contract.update');
        Route::get('/filter/{filter}', [ContractController::class, 'filterContracts'])->name('filter');
        //Users
        Route::get('/dodaj-korisnika', [\App\Http\Controllers\UserController::class, 'create'])->name('dodaj-korisnika');
        Route::get('/korisnici', [UserController::class, 'show'])->name('korisnici');
        Route::post('/korisnici', [UserController::class, 'update'])->name('korisnici');
        //Packages
        Route::get('/kreiraj-paket/{market}', [PackageController::class, 'create'])->name('paket.create');
        Route::get('/paket/{market}', [PackageController::class, 'edit'])->name('paket.edit');
        Route::post('/paket', [PackageController::class, 'store'])->name('paket.store');
        Route::patch('/paket/{package}', [PackageController::class, 'update'])->name('paket.update');
        Route::post('/dodaj-korisnika', [\App\Http\Controllers\UserController::class, 'store'])->name('dodaj-korisnika.store');

        //Trzista
        Route::get('/trzista', [\App\Http\Controllers\TrzisteController::class, 'index'])->name('trzista.index');
        Route::get('/trzista/novo-trziste', [\App\Http\Controllers\TrzisteController::class, 'create'])->name('trzista.create');
        Route::post('/trzista', [\App\Http\Controllers\TrzisteController::class, 'store'])->name('trziste.store');

        //AfterTrziste
        Route::get('/trziste/{id}', function (\App\Models\Market $id){
           return Inertia::render('Trzista/TrzistaShow', ['market' => $id]);
        })->name('trziste.show');

        //FunctionalitiesToPackage
        Route::get('/nova-funkctionalnost/{market}', [\App\Http\Controllers\FunctionalitiesController::class, 'create'])->name('nova-funkctionalnost');
        Route::post('/nova-funkcionalnost', [\App\Http\Controllers\FunctionalitiesController::class, 'store'])->name('nova-funkcionalnost.store');
        Route::get('/paket-funkcionalnost/{market}', [\App\Http\Controllers\FunctionalitiesController::class, 'functionalityToPackage'])->name('paket-funkcionalnost.create');
        Route::post('/paket-funkcionalnost', [\App\Http\Controllers\FunctionalitiesController::class, 'storeFuncToPackage'])->name('paket-funkcionalnost.store');

    });

    require __DIR__ . '/auth.php';
