<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('/language', [App\Http\Controllers\LanguageController::class, 'index'])->name('language');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    /**
     * =====================================================
     * CÉGEK
     * =====================================================
     * 
     * Define the routes for the CompanyController resource.
     * These routes use the resource method to define common
     * CRUD operations. The names method is used to customize
     * the route names.
     */
    //Route::get('/getCompanies', [CompanyController::class, 'getCompanies'])->name('getCompanies');
    Route::resource('companies', CompanyController::class)->names([
        // The index route name is set to 'companies'.
        'index' => 'companies',
        // The create route name is set to 'companies.create'.
        'create' => 'companies.create',
        // The store route name is set to 'companies.store'.
        'store' => 'companies.store',
        // The update route name is set to 'companies.update'.
        'update' => 'companies.update',
        // The destroy route name is set to 'companies.destroy'.
        'destroy' => 'companies.destroy',
    ]);
    
    /**
     * =====================================================
     * CITIES
     * =====================================================
     * 
     * Define the routes for the CityController resource.
     * These routes use the resource method to define common
     * CRUD operations. The names method is used to customize
     * the route names.
     */
    Route::resource('/cities', CityController::class)->names([
        // The index route name is set to 'cities'.
        'index' => 'cities',
        // The create route name is set to 'cities.create'.
        'create' => 'cities.create',
        // The store route name is set to 'cities.store'.
        'store' => 'cities.store',
        // The update route name is set to 'cities.update'.
        'update' => 'cities.update',
        // The destroy route name is set to 'cities.destroy'.
        'destroy' => 'cities.destroy',
    ]);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
