<?php

use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\SubdomainStateController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Response;


/**
 * A POST /language útvonal fogadja a nyelvválasztó formot és változtatja a nyelvet.
 */
Route::post('/language', [LanguageController::class, 'index'])->name('language');

/**
 * Határozza meg az alkalmazás gyökérútvonalát.
 *
 * Ez az útvonal az „Üdvözöljük” komponenst az Inertia segítségével jeleníti meg.
 * Számos kellék az összetevőhöz, beleértve a bejelentkezést és a regisztrációt
 * elérhetőek az útvonalak, valamint a jelenlegi Laravel és PHP verziók.
 */
Route::get(uri: '/', action: function (): InertiaResponse {
    return Inertia::render(component: 'Welcome', props: [
        // Ellenőrizze, hogy a bejelentkezési útvonal meg van-e határozva
        'canLogin' => Route::has(name: 'login'),

        // Ellenőrizze, hogy a regisztrációs útvonal meg van-e határozva
        'canRegister' => Route::has(name: 'register'),

        // Szerezd meg az aktuális Laravel verziót
        'laravelVersion' => Application::VERSION,

        // Szerezd meg a PHP aktuális verzióját
        'phpVersion' => PHP_VERSION,
    ]);
});

/**
 * Határozza meg a főoldal útvonalát.
 *
 * Ez az útvonal az Auth middleware-t használja,
 * hogy biztosítsa a felhasználó bejelentkezését a rendszerbe,
 * mielőtt elérheti a főoldalt.
 */
Route::get(uri: '/dashboard', action: function (): InertiaResponse {
    return Inertia::render(component: 'Dashboard');
})->middleware(middleware: ['auth', 'verified'])->name(name: 'dashboard');

/**
 * Ezeket az útvonalakat az "auth" middleware-nek kell végrehajtania,
 * hogy ellenőrizze, hogy a felhasználó bejelentkezett-e a rendszerbe.
 */
Route::middleware(middleware: 'auth')->group(callback: function (): void {

    /**
     * =====================================================
     * CÉGEK
     * =====================================================
     *
     * Határozza meg a CompanyController erőforrás útvonalait.
     * Ezek az útvonalak a resource módszert használják a közös meghatározásához
     * CRUD műveletek. A testreszabáshoz a names metódust használjuk
     * az útvonalak nevei.
     */
    //Route::get('/getCompanies', [CompanyController::class, 'getCompanies'])->name('getCompanies');
    Route::resource('companies', CompanyController::class)->names([
        // Az index útvonal nevének beállítása „companies”.
        'index' => 'companies',
        // Az útvonal létrehozásának neve "companies.create"-re van állítva.
        'create' => 'companies.create',
        // Az üzlet útvonalának neve "companies.store"-ra van állítva.
        'store' => 'companies.store',
        // A frissítési útvonal neve "companies.update"-re van állítva.
        'update' => 'companies.update',
        // A megsemmisítési útvonal neve "companies.destroy"-ra van állítva.
        'destroy' => 'companies.destroy',
    ]);

    /**
     * =====================================================
     * VÁROSOK
     * =====================================================
     */
    //Route::resource('/cities', CityController::class)->names([
    //    'index' => 'cities',
    //    'create' => 'cities.create',
    //    'store' => 'cities.store',
    //    'update' => 'cities.update',
    //    'destroy' => 'cities.destroy',
    //]);
    Route::get(uri: '/cities', action: [CityController::class, 'index'])->name(name: 'cities');

    /**
     * Ez az útvonal a ProfileController osztály edit metódusát hívja meg,
     * hogy megjelenítse a profil szerkesztési oldalát.
     */
    Route::get(uri: '/profile', action: [ProfileController::class, 'edit'])->name(name: 'profile.edit');
    /**
     * Ez az útvonal a ProfileController osztály update metódusát hívja meg,
     * hogy frissítse a profil adatait.
     */
    Route::patch(uri: '/profile', action: [ProfileController::class, 'update'])->name(name: 'profile.update');
    /**
     * Ez az útvonal a ProfileController osztály destroy metódusát hívja meg,
     * hogy törölje a felhasználó profilját.
     */
    Route::delete(uri: '/profile', action: [ProfileController::class, 'destroy'])->name(name: 'profile.destroy');

    /**
     * =====================================================
     * ORSZÁGOK
     * =====================================================
     */
    // Ez az útvonal a CountryController osztály index metódusát hívja meg,
    // hogy megjelenítse az országok listáját.
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
