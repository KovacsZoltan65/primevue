<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompSettingController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SettingsMetadataController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\SubdomainStateController;
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

    /**
     * =====================================================
     * SETTINGS
     * =====================================================
     */
    Route::get('/app_settings', [AppSettingController::class, 'index'])->name('app_settings');
    Route::get('/comp_settings', [CompSettingController::class, 'index'])->name('comp_settings');
    Route::get('/settings_metadata', [SettingsMetadataController::class, 'index'])->name('settings_metadata');

    /**
     * =====================================================
     * ERROR
     * =====================================================
     *
     */
    Route::get('/error_log', [ErrorController::class, 'index'])->name('error_log');

    /**
     * =======================================================
     * HIERARCHIA
     * =======================================================
     */

    /**
     * =====================================================
     * CÉGEK
     * =====================================================
     *
     * Határozza meg a CompanyController erőforrás útvonalait.
     * Ezek az útvonalak az erőforrás módszert használják a közös meghatározásához
     * CRUD műveletek. A testreszabáshoz a names metódust használjuk
     * az útvonalak nevei.
     */
    //Route::get('/getCompanies', [CompanyController::class, 'getCompanies'])->name('getCompanies');
    Route::resource('companies', CompanyController::class)->names([
        // Az index útvonal nevének beállítása „cégek”.
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

    /**
     * =======================================================
     * PERSONS
     * =======================================================
     */
    Route::get('/persons', [PersonController::class, 'index'])->name('persons');

    /**
     * =======================================================
     * ENTITIES
     * =======================================================
     */
    Route::get('/entities', [EntityController::class, 'index'])->name('entities');});

require __DIR__.'/auth.php';
