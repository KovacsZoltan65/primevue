<?php

use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\SubdomainStateController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });

/**
 * =======================================================
 * LANGUAGES
 * =======================================================
 */
Route::get('/languages', [LanguageController::class, 'getLanguages'])->name('get.languages');

/**
 * =======================================================
 * COMPANIES
 * =======================================================
 * A cégek listájának lekérése az API-n keresztül.
 * 
 * @param CompanyController $companyController A cégvezérlő példány.
 * @return JsonResponse A vállalatok listáját tartalmazó JSON-válasz.
 */
//Route::get('/items', [CompanyController::class, 'getCompanies'])->name('api.get.companies');
Route::get('/companies', [CompanyController::class, 'getCompanies'])->name('api.get.companies');

/**
 * Hozzon létre új céget az API-n keresztül.
 *
 * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
 * @param CompanyController $companyController A CompanyController példány.
 * @return JsonResponse A létrehozott vállalatot tartalmazó JSON-válasz.
 */
Route::post('/items', [CompanyController::class, 'createCompany'])->name('api.create.company');

/**
 * Frissítsen egy meglévő céget.
 *
 * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
 * @param int $id A frissítendő cég azonosítója.
 * @return JsonResponse A frissített vállalatot tartalmazó JSON-válasz.
 */
Route::put('/items/{id}', [CompanyController::class, 'updateCompany'])->name('api.update.company')->where('id', '[0-9]+');

/**
 * Töröljön egy meglévő céget.
 *
 * @param int $id A törölni kívánt cég azonosítója.
 * @return JsonResponse A törölt vállalatot tartalmazó JSON-válasz.
 */
Route::delete('/items/{id}', [CompanyController::class, 'deleteCompany'])->name('api.delete.company')->where('id', '[0-9]+');

/**
 * =======================================================
 * VÁROSOK
 * =======================================================
 */
Route::get('/cities', [CityController::class, 'getCities'])->name('api.get.cities');

Route::get('/cities/{id}', [CityController::class, 'getCity'])->name('api.get.city_by_id')->where('id', '[0-9]+');
Route::get('/cities/name/{name}', [CityController::class, 'getCityByName'])->name('api.get.city_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

Route::post('/cities', [CityController::class, 'createCity'])->name('api.post.cities');
Route::put('/cities/{id}', [CityController::class, 'updateCity'])->name('api.put.cities')->where('id', '[0-9]+');
Route::delete('/cities/{id}', [CityController::class, 'deleteCity'])->name('api.delete.cities')->where('id', '[0-9]+');
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

/**
 * =======================================================
 * VALIDATION
 * =======================================================
 */

Route::post(
    '/submit-form', 
    [
        \App\Http\Controllers\ValidationController::class, 
        'updateVal_03'
    ]
)->name('api.post.submit-form');

/*
Route::post(
    '/submit-form', 
    function(Request $request){
        \Log::info('API');
    }
)->name('api.post.submit-form');
*/
/**
 * =======================================================
 * ORSZÁGOK
 * =======================================================
 */
Route::get('/countries', [CountryController::class, 'getCountries'])->name('api.get.countries');

Route::get('/countries/{id}', [CountryController::class, 'getCountry'])->name('api.get.country_by_id')->where('id', '[0-9]+');
Route::get('/countries/name/{name}', [CountryController::class, 'getCountryByName'])->name('api.get.country_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

Route::post('/countries', [CountryController::class, 'createCountry'])->name('api.post.countries');
Route::put('/countries/{id}', [CountryController::class, 'updateCountry'])->name('api.put.countries')->where('id', '[0-9]+');
Route::delete('/countries/{id}', [CountryController::class, 'deleteCountry'])->name('api.delete.countries')->where('id', '[0-9]+');

/**
 * =======================================================
 * JOGI SZEMÉLYEK (legal entity)
 * =======================================================
 */
Route::get('/entities', [EntityController::class, 'getEntities'])->name('api.get.entities');

Route::get('/entities/{id}', [EntityController::class, 'getEntity'])->name('api.get.entity_by_id')->where('id', '[0-9]+');
Route::get('/entities/name/{name}', [EntityController::class, 'getEntityByName'])->name('api.get.entity_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

Route::post('/entities', [EntityController::class, 'createEntity'])->name('api.post.entities');
Route::put('/entities/{id}', [EntityController::class, 'updateEntity'])->name('api.put.entities')->where('id', '[0-9]+');
Route::delete('/entities/{id}', [EntityController::class, 'deleteEntity'])->name('api.delete.entities')->where('id', '[0-9]+');

/**
 * =======================================================
 * MEGYÉK
 * =======================================================
 */
Route::get('/regions', [RegionController::class, 'getRegions'])->name('api.get.regions');
Route::post('/regions', [RegionController::class, 'createRegion'])->name('api.post.regions');
Route::put('/regions/{id}', [RegionController::class, 'updateRegion'])->name('api.put.regions')->where('id', '[0-9]+');
Route::delete('/regions/{id}', [RegionController::class, 'deleteRegion'])->name('api.delete.regions')->where('id', '[0-9]+');

/**
 * =======================================================
 * FELHASZNÁLÓK
 * =======================================================
 */
Route::get('/users', [UserController::class, 'getUsers'])->name('api.get.users');
Route::get('/users/{id}', [UserController::class, 'getUser'])->name('api.get.user')->where('id', '[0-9]+');
Route::get('/users/name/{name}', [EntityController::class, 'getEntityByName'])->name('api.get.user_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
Route::post('/users', [UserController::class, 'createUsers'])->name('api.post.users');
Route::put('/users/{id}', [UserController::class, 'updateUsers'])->name('api.put.users')->where('id', '[0-9]+');
Route::put('/users/password/{id}', [UserController::class, 'updatePassword'])->name('api.put.password')->where('id', '[0-9]+');
Route::delete('/users/{id}', [UserController::class, 'deleteUsers'])->name('api.delete.users')->where('id', '[0-9]+');

/**
 * =======================================================
 * SEREPKÖRÖK (ROLES)
 * =======================================================
 */
Route::get('/roles', [RoleController::class, 'getRoles'])->name('api.get.roles');
Route::post('/roles', [RoleController::class, 'createRoles'])->name('api.post.roles');
Route::put('/roles/{id}', [RoleController::class, 'updateRoles'])->name('api.put.roles')->where('id', '[0-9]+');
Route::delete('/roles/{id}', [RoleController::class, 'deleteRoles'])->name('api.delete.roles')->where('id', '[0-9]+');

/**
 * =======================================================
 * ENGEDÉLYEK (PERMISSIONS)
 * =======================================================
 */
Route::get('/permissions', [PermissionController::class, 'getPermissions'])->name('api.get.permissions');
Route::post('/permissions', [PermissionController::class, 'createPermissions'])->name('api.post.permissions');
Route::put('/permissions/{id}', [PermissionController::class, 'updatePermissions'])->name('api.put.permissions')->where('id', '[0-9]+');
Route::delete('/permissions/{id}', [PermissionController::class, 'deletePermissions'])->name('api.delete.permissions')->where('id', '[0-9]+');

/**
 * =======================================================
 * SUBDOMAINS
 * =======================================================
 */
Route::get('/subdomains', [SubdomainController::class, 'getSubdomains'])->name('api.get.subdomains');
Route::post('/subdomains', [SubdomainController::class, 'createSubdomains'])->name('api.post.subdomains');
Route::put('/subdomains/{id}', [SubdomainController::class, 'updateSubdomains'])->name('api.put.subdomains')->where('id', '[0-9]+');
Route::delete('/subdomains/{id}', [SubdomainController::class, 'deleteSubdomains'])->name('api.delete.subdomains')->where('id', '[0-9]+');

/**
 * =======================================================
 * SUBDOMAIN STATES
 * =======================================================
 */
Route::get('/subdomain_states', [SubdomainStateController::class, 'getSubdomainStates'])->name('api.get.subdomain_states');
Route::post('/subdomain_states', [SubdomainStateController::class, 'createSubdomainStates'])->name('api.post.subdomain_states');
Route::put('/subdomain_states/{id}', [SubdomainStateController::class, 'updateSubdomainStates'])->name('api.put.subdomain_states')->where('id', '[0-9]+');
Route::delete('/subdomain_states/{id}', [SubdomainStateController::class, 'deleteSubdomainStates'])->name('api.delete.subdomain_states')->where('id', '[0-9]+');