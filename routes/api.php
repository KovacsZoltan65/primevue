<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CompSettingController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SettingsMetadataController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\SubdomainStateController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });

Route::middleware(['web', 'auth'])->group(function () {

    /**
     * =======================================================
     * ERROR HANDLING
     * =======================================================
     */
    Route::post('/client-errors', [ErrorController::class, 'logClientError']);
    Route::post('/client_validation_errors', [ErrorController::class, 'logClientValidationError']);

    Route::get('/server-errors/by_id/{errorId}', [ErrorController::class, 'getErrorById']);
    Route::get('/server-errors/by_unique_id/{uniqueErrorId}', [ErrorController::class, 'getErrorByUniqueId']);

    Route::prefix('error-logs')
        ->name('error-logs.')
        ->group(function() {
            Route::get('/', [ErrorController::class, 'index'])->name('index');
            Route::get('/{id}', [ErrorController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::delete('/{id}', [ErrorController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        });

    /**
     * =======================================================
     * LANGUAGES
     * =======================================================
     */
    Route::get('/languages', [LanguageController::class, 'getLanguages'])->name('get.languages');

    /**
     * =======================================================
     * HIERARCHIA
     * =======================================================
     */
    // Szülö hozzáadása
    Route::post('/entity-hierarchy/{childId}/add-parent', [HierarchyController::class, 'addParent']);
    // Gyermek hozzáadása
    Route::post('/entity-hierarchy/{parentId}/add-child', [HierarchyController::class, 'addChild'])->name('addChild');
    // Hierarchia lekérdezése
    Route::get('/entity-hierarchy/{entityId}/hierarchy', [HierarchyController::class, 'getHierarchy']);
    // Gyermek eltávolítása
    Route::delete('/entity-hierarchy/{parentId}/remove-child', [HierarchyController::class, 'removeChild']);
    // Nagyfőnök lekérése
    Route::get('/entity-hierarchy/big-bosses', [HierarchyController::class, 'getBigBosses']);
    // Hierarchia épségének ellenőrzése
    Route::get('/entity-hierarchy/validate', [HierarchyController::class, 'validateHierarchy']);
    // Izolált entitások keresése
    Route::get('/entity-hierarchy/isolated-entities', [HierarchyController::class, 'checkIsolatedEntities']);
    // Egy vezető beosztottjainak áthelyezése egy másik vezető alá
    Route::post('/entity-hierarchy/{fromManagerId}/transfer-subordinates', [HierarchyController::class, 'transferSubordinates']);
    // Két vezető beosztottjainak kicserélése
    Route::post('/entity-hierarchy/swap-subordinates', [HierarchyController::class, 'swapSubordinates']);
    // Dolgozói szint megállapítása
    Route::get('/employee/{id}/role', [HierarchyController::class, 'getEmployeeRole']);

    /**
     * =======================================================
     * MENÜ
     * =======================================================
     */
    Route::get('/menu-items', [MenuController::class, 'getSortedMenuItems'])->name('get.menu');

    Route::resource('menu-items', MenuController::class);

    /*
    Route::prefix('admin/menu')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('menu.index'); // Menüpontok listázása
        Route::post('/', [MenuController::class, 'store'])->name('menu.store'); // Új menüpont létrehozása
        Route::get('/{menuItem}', [MenuController::class, 'show'])->name('menu.show'); // Egyedi menüpont megtekintése
        Route::put('/{menuItem}', [MenuController::class, 'update'])->name('menu.update'); // Menüpont frissítése
        Route::delete('/{menuItem}', [MenuController::class, 'destroy'])->name('menu.destroy'); // Menüpont törlése
    });
    */
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
    Route::get('/companies/{id}', [CompanyController::class, 'getCompany'])->name('api.get.company')->where('id', '[0-9]+');
    Route::get('/companies/name/{name}', [CompanyController::class, 'getCompanyByName'])->name('api.get.company_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post( '/companies', [CompanyController::class, 'createCompany'] )->name('api.post.companies');
    Route::put('/companies/{id}', [CompanyController::class, 'updateCompany'])->name('api.put.companies')->where('id', '[0-9]+');
    Route::delete('/companies/{id}', [CompanyController::class, 'deleteCompany'])->name('api.delete.company')->where('id', '[0-9]+');
    Route::delete('/companies', [CompanyController::class, 'deleteCompanies'])->name('api.delete.companies');

    /**
     * =====================================================
     * SETTINGS
     * =====================================================
     */
    Route::get('/app_settings', [AppSettingController::class, 'getAppSettings']);
    Route::get('/app_settings/{id}', [AppSettingController::class, 'getApptSetting']);
    Route::get('/app_settings/key/{key}', [AppSettingController::class, 'getAppSettingByKey']);
    Route::post('/app_settings', [AppSettingController::class, 'createAppSetting']);
    Route::put('/app_settings/{id}', [AppSettingController::class, 'updateAppSetting']);

    Route::get('/comp_settings', [CompSettingController::class, 'getCompSettings']);
    Route::get('/comp_settings/{id}', [CompSettingController::class, 'getCompSetting']);
    Route::get('/comp_settings/key/{key}', [CompSettingController::class, 'getCompSettingByKey']);
    Route::post('/comp_settings', [CompSettingController::class, 'createCompSetting']);
    Route::put('/comp_settings/{id}', [CompSettingController::class, 'updateCompSetting']);

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
     *
     * A városokhoz tartozó API útvonalak.
     * A városok listáját szolgáltatja az API-n keresztül.
     *
     * @param CityController $cityController A CityController példány.
     * @return JsonResponse A városok listáját tartalmazó JSON-válasz.
     */
    Route::get('/cities', [CityController::class, 'getCities'])->name('api.get.cities');

    /**
     * Egy város lekérése azonosító alapján az API-n keresztül.
     *
     * @param int $id A lekérdezni kívánt város azonosítója.
     * @return JsonResponse A várost tartalmazó JSON-válasz.
     */
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
    Route::get('/roles/{id}', [RoleController::class, 'getRole'])->name('api.get.role')->where('id', '[0-9]+');
    Route::get('/roles/name/{name}', [RoleController::class, 'getRolesByName'])->name('api.get.role_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/roles', [RoleController::class, 'createRole'])->name('api.post.roles');
    Route::put('/roles/{id}', [RoleController::class, 'updateRoles'])->name('api.put.roles')->where('id', '[0-9]+');
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRoles'])->name('api.delete.roles')->where('id', '[0-9]+');

    /**
     * =======================================================
     * ENGEDÉLYEK (PERMISSIONS)
     * =======================================================
     */
    Route::get('/permissions', [PermissionController::class, 'getPermissions'])->name('api.get.permissions');
    Route::get('/permissions/{id}', [PermissionController::class, 'getPermission'])->name('api.get.permission')->where('id', '[0-9]+');
    Route::get('/permissions/name/{name}', [PermissionController::class, 'getPermissionByName'])->name('api.get.permission_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/permissions', [PermissionController::class, 'createPermissions'])->name('api.post.permissions');
    Route::put('/permissions/{id}', [PermissionController::class, 'updatePermissions'])->name('api.put.permissions')->where('id', '[0-9]+');
    Route::delete('/permissions/{id}', [PermissionController::class, 'deletePermissions'])->name('api.delete.permissions')->where('id', '[0-9]+');

    /**
     * =======================================================
     * SUBDOMAINS
     * =======================================================
     */
    Route::get('/subdomains', [SubdomainController::class, 'getSubdomains'])->name('api.get.subdomains');
    Route::get('/subdomains/{id}', [SubdomainController::class, 'getSubdomain'])->name('api.get.subdomains')->where('id', '[0-9]+');
    Route::get('/subdomains/name/{name}', [SubdomainController::class, 'getSubdomainByName'])->name('api.get.subdomain_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/subdomains', [SubdomainController::class, 'createSubdomains'])->name('api.post.subdomains');
    Route::put('/subdomains/{id}', [SubdomainController::class, 'updateSubdomains'])->name('api.put.subdomains')->where('id', '[0-9]+');
    Route::delete('/subdomains/{id}', [SubdomainController::class, 'deleteSubdomains'])->name('api.delete.subdomains')->where('id', '[0-9]+');

    /**
     * =======================================================
     * SUBDOMAIN STATES
     * =======================================================
     */
    Route::get('/subdomain_states', [SubdomainStateController::class, 'getSubdomainStates'])->name('api.get.subdomain_states');
    Route::get('/subdomain_states/{id}', [SubdomainStateController::class, 'getSubdomainState'])->name('api.get.subdomain_state')->where('id', '[0-9]+');
    Route::get('/subdomain_states/name/{name}', [SubdomainStateController::class, 'getSubdomainStateByName'])->name('api.get.subdomain_state_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/subdomain_states', [SubdomainStateController::class, 'createSubdomainStates'])->name('api.post.subdomain_states');
    Route::put('/subdomain_states/{id}', [SubdomainStateController::class, 'updateSubdomainState'])->name('api.put.subdomain_states')->where('id', '[0-9]+');
    Route::delete('/subdomain_states/{id}', [SubdomainStateController::class, 'deleteSubdomainStates'])->name('api.delete.subdomain_states')->where('id', '[0-9]+');

    /**
     * =======================================================
     * PERSONS
     * =======================================================
     */
    Route::get('/persons', [PersonController::class, 'getPersons'])->name('api.get.persons');
    Route::get('/persons/{id}', [PersonController::class, 'getPerson'])->name('api.get.person')->where('id', '[0-9]+');
    Route::get('/persons/name/{name}', [PersonController::class, 'getPersonByName'])->name('api.get.person_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/persons', [PersonController::class, 'createPersons'])->name('api.post.persons');
    Route::put('/persons/{id}', [PersonController::class, 'updatePersons'])->name('api.put.persons')->where('id', '[0-9]+');
    Route::delete('/persons/{id}', [PersonController::class, 'deletePersons'])->name('api.delete.persons')->where('id', '[0-9]+');

    /**
     * =======================================================
     * ENTITIES
     * =======================================================
     */
    Route::get('/entities', [EntityController::class, 'getEntities'])->name('api.get.entities');
    Route::get('/entities/{id}', [EntityController::class, 'getEntity'])->name('api.get.entity')->where('id', '[0-9]+');
    Route::get('/entities/name/{name}', [EntityController::class, 'getEntityByName'])->name('api.get.entity_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/entities', [EntityController::class, 'createEntities'])->name('api.post.entities');
    Route::put('/entities/{id}', [EntityController::class, 'updateEntities'])->name('api.put.entities')->where('id', '[0-9]+');
    Route::delete('/entities/{id}', [EntityController::class, 'deleteEntities'])->name('api.delete.entities')->where('id', '[0-9]+');

});
