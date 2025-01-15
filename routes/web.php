<?php

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
     * SETTINGS
     * =====================================================
     */
    Route::get('/app_settings', [App\Http\Controllers\AppSettingController::class, 'index'])->name('app_settings');
    Route::get('/comp_settings', [App\Http\Controllers\CompSettingController::class, 'index'])->name('comp_settings');
    Route::get('/settings_metadata', [\App\Http\Controllers\SettingsMetadataController::class, 'index'])->name('settings_metadata');

    /**
     * =====================================================
     * ERROR
     * =====================================================
     *
     */
    Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('error_log');

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
    Route::get('/getCompanies', [\App\Http\Controllers\CompanyController::class, 'getCompanies'])->name('getCompanies');
    Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'index'])->name('');
    /*
    Route::resource('companies', \App\Http\Controllers\CompanyController::class)->names([
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
    */

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
    Route::get('/cities', [App\Http\Controllers\CityController::class, 'index'])->name('cities');

    /**
     * =====================================================
     * PROFIL
     * =====================================================
     */
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

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
    Route::get('/countries', [\App\Http\Controllers\CountryController::class, 'index'])->name('countries');

    /**
     * =====================================================
     * MEGYÉK
     * =====================================================
     */
    Route::get('/regions', [App\Http\Controllers\RegionController::class, 'index'])->name('regions');

    /**
     * =======================================================
     * FELHASZNÁLÓK
     * =======================================================
     */
    Route::get('/users', [App\Http\Controllers\Auth\UserController::class, 'index'])->name('users');

    /**
     * =======================================================
     * SZEREPKÖRÖK
     * =======================================================
     */
    Route::get('/roles', [\App\Http\Controllers\Auth\RoleController::class, 'index'])->name('roles');

    /**
     * =======================================================
     * ENGEDÉLYEK
     * =======================================================
     */
    Route::get('/permissions', [App\Http\Controllers\Auth\PermissionController::class, 'index'])->name('permissions');

    /**
     * =======================================================
     * SUBDOMAINS
     * =======================================================
     */
    Route::get('/subdomains', [\App\Http\Controllers\SubdomainController::class, 'index'])->name('subdomains');

    /**
     * =======================================================
     * SUBDOMAIN STATES
     * =======================================================
     */
    Route::get('/subdomain_states', [App\Http\Controllers\SubdomainStateController::class, 'index'])->name('subdomain_states');

    /**
     * =======================================================
     * ACCESS CONTROLL SYSTEMS (ACS)
     * =======================================================
     */
    Route::get('/acs_systems', [App\Http\Controllers\ACSController::class, 'index'])->name('acs_systems');
    
    /**
     * =======================================================
     * PERSONS
     * =======================================================
     */
    Route::get('/persons', [\App\Http\Controllers\PersonController::class, 'index'])->name('persons');

    /**
     * =======================================================
     * ENTITIES
     * =======================================================
     */
    Route::get('/entities', [\App\Http\Controllers\EntityController::class, 'index'])->name('entities');});

require __DIR__.'/auth.php';
