<?php

use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });

// =======================================================
// CÉGEK
// =======================================================

/**
 * A cégek listájának lekérése az API-n keresztül.
 *
 * @param CompanyController $companyController A cégvezérlő példány.
 * @return \Illuminate\Http\JsonResponse A vállalatok listáját tartalmazó JSON-válasz.
 */
//Route::get('/items', [CompanyController::class, 'getCompanies'])->name('api.get.companies');
Route::get('/companies', [CompanyController::class, 'getCompanies'])->name('api.get.companies');

/**
 * Hozzon létre új céget az API-n keresztül.
 *
 * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
 * @param CompanyController $companyController A CompanyController példány.
 * @return \Illuminate\Http\JsonResponse A létrehozott vállalatot tartalmazó JSON-válasz.
 */
Route::post('/items', [CompanyController::class, 'createCompany'])->name('api.create.company');

/**
 * Frissítsen egy meglévő céget.
 *
 * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
 * @param int $id A frissítendő cég azonosítója.
 * @return \Illuminate\Http\JsonResponse A frissített vállalatot tartalmazó JSON-válasz.
 */
Route::put('/items/{id}', [CompanyController::class, 'updateCompany'])->name('api.update.company')->where('id', '[0-9]+');

/**
 * Töröljön egy meglévő céget.
 *
 * @param int $id A törölni kívánt cég azonosítója.
 * @return \Illuminate\Http\JsonResponse A törölt vállalatot tartalmazó JSON-válasz.
 */
Route::delete('/items/{id}', [CompanyController::class, 'deleteCompany'])->name('api.delete.company')->where('id', '[0-9]+');

// =======================================================
// VÁROSOK
// =======================================================
Route::get('/cities', [CityController::class, 'getCities'])->name('api.get.cities');
Route::post('/cities', [CityController::class, 'createCity'])->name('api.post.cities');
Route::put('/cities/{id}', [CityController::class, 'updateCity'])->name('api.put.cities');
//Route::post('/cities', [CityController::class, 'createCity'])->name('apicities.create');
//Route::put('/cities/{id}', [CityController::class, 'updateCity'])->name('api.cities.update')->where('id', '[0-9]+');
//Route::delete('/cities/{id}', [CityController::class, 'deleteCity'])->name('api.cities.delete')->where('id', '[0-9]+');

/*
Route::resource('/cities', CityController::class)->names([
        'index'   => 'api.cities',
        'create'  => 'api.cities.create',
        'store'   => 'api.cities.store',
        'update'  => 'api.cities.update',
        'destroy' => 'api.cities.destroy',
    ]);
*/

// =======================================================
// CÉGEK
// =======================================================
Route::get('/countries', [CountryController::class, 'getCountries'])->name('api.get.countries');
Route::post('/countries', [CountryController::class, 'createCountry'])->name('api.post.countries');
Route::put('/countries/{id}', [CountryController::class, 'updateCountry'])->name('api.put.countries');
Route::delete('/countries/{id}', [CountryController::class, 'deleteCountry'])->name('api.delete.countries');

// =======================================================
// FELHASZNÁLÓK
// =======================================================
Route::get('/users', [UserController::class, 'getUsers'])->name('api.get.users');
Route::post('/users', [UserController::class, 'createUsers'])->name('api.post.users');
Route::put('/users/{id}', [UserController::class, 'updateUsers'])->name('api.put.users');
Route::delete('/users/{id}', [UserController::class, 'deleteUsers'])->name('api.delete.users');

// =======================================================
// SZEREPKÖRÖK
// =======================================================
Route::get('/roles', [RoleController::class, 'getRoles'])->name('api.get.roles');
Route::post('/roles', [RoleController::class, 'createRoles'])->name('api.post.roles');
Route::put('/roles/{id}', [RoleController::class, 'updateRoles'])->name('api.put.roles');
Route::delete('/roles/{id}', [RoleController::class, 'deleteRoles'])->name('api.delete.roles');

// =======================================================
// ENGEDÉLYEK
// =======================================================
Route::get('/permissions', [PermissionController::class, 'getPermissions'])->name('api.get.permissions');
Route::post('/permissions', [PermissionController::class, 'createPermissions'])->name('api.post.permissions');
Route::put('/permissions/{id}', [PermissionController::class, 'updatePermissions'])->name('api.put.permissions');
Route::delete('/permissions/{id}', [PermissionController::class, 'deletePermissions'])->name('api.delete.permissions');