<?php

use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\SubdomainStateController;
use App\Http\Controllers\ValidationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('/language', [LanguageController::class, 'index'])->name('language');

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
    
    // VALIDATION
    Route::get('/validation', [ValidationController::class, 'validation'])->name('validation');
    Route::get('/validation_01', [ValidationController::class, 'validation_01'])->name('validation_01');
    Route::get('/validation_02', [ValidationController::class, 'validation_02'])->name('validation_02');

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
     * VÁROSOK
     * =====================================================
     * 
     * Határozza meg a CityController erőforrás útvonalait.
     * Ezek az útvonalak az erőforrás módszert használják a közös meghatározásához
     * CRUD műveletek. A testreszabáshoz a names metódust használjuk
     * az útvonalak nevei.
     */
    //Route::resource('/cities', CityController::class)->names([
    //    'index' => 'cities',
    //    'create' => 'cities.create',
    //    'store' => 'cities.store',
    //    'update' => 'cities.update',
    //    'destroy' => 'cities.destroy',
    //]);
    Route::get('/cities', [CityController::class, 'index'])->name('cities');
    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    /**
     * =====================================================
     * ORSZÁGOK
     * =====================================================
     * 
     * Határozza meg a CountryController erőforrás útvonalait.
     * Ezek az útvonalak az erőforrás módszert használják a közös meghatározásához
     * CRUD műveletek. A testreszabáshoz a names metódust használjuk
     * az útvonalak nevei.
     */
    Route::get('/countries', [CountryController::class, 'index'])->name('countries');
    
    /**
     * =====================================================
     * MEGYÉK
     * =====================================================
     */
    Route::get('/regions', [RegionController::class, 'index'])->name('regions');
    
    /**
     * =======================================================
     * FELHASZNÁLÓK
     * =======================================================
     */
    Route::get('/users', [UserController::class, 'index'])->name('users');

    /**
     * =======================================================
     * SZEREPKÖRÖK
     * =======================================================
     */
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');

    /**
     * =======================================================
     * ENGEDÉLYEK
     * =======================================================
     */
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    
    /**
     * =======================================================
     * SUBDOMAINS
     * =======================================================
     */
    Route::get('/subdomains', [SubdomainController::class, 'index'])->name('subdomains');
    
    /**
     * =======================================================
     * SUBDOMAIN STATES
     * =======================================================
     */
    Route::get('/subdomain_states', [SubdomainStateController::class, 'index'])->name('subdomain_states');
});

require __DIR__.'/auth.php';
