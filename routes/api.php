<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });


/**
 * =======================================================
 * TESZT FUTTÁSA
 * ======================================================
 */
Route::post( '/test/companies', [\App\Http\Controllers\CompanyController::class, 'createCompany'] )->name('api.post.companies');

Route::middleware(['web', 'auth'])->group(function () {

    /**
     * =======================================================
     * ERROR HANDLING
     * =======================================================
     */
    Route::post('/client-errors', [App\Http\Controllers\ActivityController::class, 'logClientError']);
    Route::post('/client_validation_errors', [App\Http\Controllers\ActivityController::class, 'logClientValidationError']);

    Route::get('/server-errors/by_id/{errorId}', [App\Http\Controllers\ActivityController::class, 'getErrorById']);
    Route::get('/server-errors/by_unique_id/{uniqueErrorId}', [App\Http\Controllers\ActivityController::class, 'getErrorByUniqueId']);

    Route::prefix('error-logs')
        ->name('error-logs.')
        ->group(function() {
            Route::get('/', [App\Http\Controllers\ActivityController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::delete('/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        });

    /**
     * =======================================================
     * LANGUAGES
     * =======================================================
     */
    Route::get('/languages', [App\Http\Controllers\LanguageController::class, 'getLanguages'])->name('get.languages');

    /**
     * =======================================================
     * MENÜ
     * =======================================================
     */
    Route::get('/menu-items', [App\Http\Controllers\MenuController::class, 'getSortedMenuItems'])->name('get.menu');

    Route::resource('menu-items', App\Http\Controllers\MenuController::class);

    /**
     * =======================================================
     * ACCESS CONTROLL SYSTEMS (ACS)
     * =======================================================
     */
    Route::get('/acs_systems', [App\Http\Controllers\ACSController::class, 'getACSs'])->name('api.get.acss');
    Route::get('/acs_systems/{id}', [App\Http\Controllers\ACSController::class, 'getACS'])->name('api.get.acs')->where('id', '[0-9]+');
    Route::get('/acs_systems/name/{name}', [App\Http\Controllers\ACSController::class, 'getACSByName'])->name('api.get.acs_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/acs_systems', [App\Http\Controllers\ACSController::class, 'createACS'])->name('api.post.acs');
    Route::put('/acs_systems/{id}', [App\Http\Controllers\ACSController::class, 'updateACS'])->name('api.put.acs')->where('id', '[0-9]+');

    Route::delete('/acs_systems/{id}', [App\Http\Controllers\ACSController::class, 'deleteACS'])->name('api.delete.acs')->where('id', '[0-9]+');
    Route::delete('/acs_systems', [\App\Http\Controllers\ACSController::class, 'deleteACSs'])->name('api.delete.acss');
    
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
     * @param \App\Http\Controllers\CompanyController $companyController A cégvezérlő példány.
     * @return JsonResponse A vállalatok listáját tartalmazó JSON-válasz.
     */
    //Route::get('/items', [CompanyController::class, 'getCompanies'])->name('api.get.companies');
    Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'getCompanies'])->name('api.get.companies');
    Route::get('/companies/{id}', [\App\Http\Controllers\CompanyController::class, 'getCompany'])->name('api.get.company')->where('id', '[0-9]+');
    Route::get('/companies/name/{name}', [\App\Http\Controllers\CompanyController::class, 'getCompanyByName'])->name('api.get.company_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post( '/companies', [\App\Http\Controllers\CompanyController::class, 'createCompany'] )->name('api.post.companies');

    Route::put('/companies/{id}', [\App\Http\Controllers\CompanyController::class, 'updateCompany'])->name('api.put.companies')->where('id', '[0-9]+');

    Route::delete('/companies/{id}', [\App\Http\Controllers\CompanyController::class, 'deleteCompany'])->name('api.delete.company')->where('id', '[0-9]+');
    Route::delete('/companies', [\App\Http\Controllers\CompanyController::class, 'deleteCompanies'])->name('api.delete.companies');

    /**
     * =====================================================
     * SETTINGS
     * =====================================================
     */
    Route::get('/app_settings', [App\Http\Controllers\AppSettingController::class, 'getAppSettings']);
    Route::get('/app_settings/{id}', [App\Http\Controllers\AppSettingController::class, 'getApptSetting']);
    Route::get('/app_settings/key/{key}', [App\Http\Controllers\AppSettingController::class, 'getAppSettingByKey']);
    Route::post('/app_settings', [App\Http\Controllers\AppSettingController::class, 'createAppSetting']);
    Route::put('/app_settings/{id}', [App\Http\Controllers\AppSettingController::class, 'updateAppSetting']);

    Route::get('/comp_settings', [App\Http\Controllers\CompSettingController::class, 'getCompSettings']);
    Route::get('/comp_settings/{id}', [App\Http\Controllers\CompSettingController::class, 'getCompSetting']);
    Route::get('/comp_settings/key/{key}', [App\Http\Controllers\CompSettingController::class, 'getCompSettingByKey']);
    Route::post('/comp_settings', [App\Http\Controllers\CompSettingController::class, 'createCompSetting']);
    Route::put('/comp_settings/{id}', [App\Http\Controllers\CompSettingController::class, 'updateCompSetting']);

    /**
     * Hozzon létre új céget az API-n keresztül.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @param \App\Http\Controllers\CompanyController $companyController A CompanyController példány.
     * @return JsonResponse A létrehozott vállalatot tartalmazó JSON-válasz.
     */
    //Route::post('/items', [\App\Http\Controllers\CompanyController::class, 'createCompany'])->name('api.create.company');

    /**
     * Frissítsen egy meglévő céget.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @param int $id A frissítendő cég azonosítója.
     * @return JsonResponse A frissített vállalatot tartalmazó JSON-válasz.
     */
    //Route::put('/items/{id}', [\App\Http\Controllers\CompanyController::class, 'updateCompany'])->name('api.update.company')->where('id', '[0-9]+');

    /**
     * Töröljön egy meglévő céget.
     *
     * @param int $id A törölni kívánt cég azonosítója.
     * @return JsonResponse A törölt vállalatot tartalmazó JSON-válasz.
     */
    //Route::delete('/items/{id}', [\App\Http\Controllers\CompanyController::class, 'deleteCompany'])->name('api.delete.company')->where('id', '[0-9]+');

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
    Route::get('/cities', [App\Http\Controllers\CityController::class, 'getCities'])->name('api.get.cities');

    /**
     * Egy város lekérése azonosító alapján az API-n keresztül.
     *
     * @param int $id A lekérdezni kívánt város azonosítója.
     * @return JsonResponse A várost tartalmazó JSON-válasz.
     */
    Route::get('/cities/{id}', [App\Http\Controllers\CityController::class, 'getCity'])->name('api.get.city_by_id')->where('id', '[0-9]+');
    Route::get('/cities/name/{name}', [App\Http\Controllers\CityController::class, 'getCityByName'])->name('api.get.city_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

    Route::post('/cities', [App\Http\Controllers\CityController::class, 'createCity'])->name('api.post.cities');
    Route::put('/cities/{id}', [App\Http\Controllers\CityController::class, 'updateCity'])->name('api.put.cities')->where('id', '[0-9]+');
    Route::delete('/cities/{id}', [App\Http\Controllers\CityController::class, 'deleteCity'])->name('api.delete.cities')->where('id', '[0-9]+');
    //Route::post('/cities', [App\Http\Controllers\CityController::class, 'createCity'])->name('apicities.create');
    //Route::put('/cities/{id}', [App\Http\Controllers\CityController::class, 'updateCity'])->name('api.cities.update')->where('id', '[0-9]+');
    //Route::delete('/cities/{id}', [App\Http\Controllers\CityController::class, 'deleteCity'])->name('api.cities.delete')->where('id', '[0-9]+');

    /*
    Route::resource('/cities', App\Http\Controllers\CityController::class)->names([
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
    Route::get('/countries', [\App\Http\Controllers\CountryController::class, 'getCountries'])->name('api.get.countries');

    Route::get('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'getCountry'])->name('api.get.country_by_id')->where('id', '[0-9]+');
    Route::get('/countries/name/{name}', [\App\Http\Controllers\CountryController::class, 'getCountryByName'])->name('api.get.country_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

    Route::post('/countries', [\App\Http\Controllers\CountryController::class, 'createCountry'])->name('api.post.countries');
    Route::put('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'updateCountry'])->name('api.put.countries')->where('id', '[0-9]+');
    Route::delete('/countries/{id}', [\App\Http\Controllers\CountryController::class, 'deleteCountry'])->name('api.delete.countries')->where('id', '[0-9]+');

    /**
     * =======================================================
     * JOGI SZEMÉLYEK (legal entity)
     * =======================================================
     */
    Route::get('/entities', [\App\Http\Controllers\EntityController::class, 'getEntities'])->name('api.get.entities');

    Route::get('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'getEntity'])->name('api.get.entity_by_id')->where('id', '[0-9]+');
    Route::get('/entities/name/{name}', [\App\Http\Controllers\EntityController::class, 'getEntityByName'])->name('api.get.entity_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');

    Route::post('/entities', [\App\Http\Controllers\EntityController::class, 'createEntity'])->name('api.post.entities');
    Route::put('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'updateEntity'])->name('api.put.entities')->where('id', '[0-9]+');
    Route::delete('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'deleteEntity'])->name('api.delete.entities')->where('id', '[0-9]+');

    /**
     * =======================================================
     * MEGYÉK
     * =======================================================
     */
    Route::get('/regions', [App\Http\Controllers\RegionController::class, 'getRegions'])->name('api.get.regions');
    Route::post('/regions', [App\Http\Controllers\RegionController::class, 'createRegion'])->name('api.post.regions');
    Route::put('/regions/{id}', [App\Http\Controllers\RegionController::class, 'updateRegion'])->name('api.put.regions')->where('id', '[0-9]+');
    Route::delete('/regions/{id}', [App\Http\Controllers\RegionController::class, 'deleteRegion'])->name('api.delete.regions')->where('id', '[0-9]+');

    /**
     * =======================================================
     * FELHASZNÁLÓK
     * =======================================================
     */
    Route::get('/users', [App\Http\Controllers\Auth\UserController::class, 'getUsers'])->name('api.get.users');
    Route::get('/users/{id}', [App\Http\Controllers\Auth\UserController::class, 'getUser'])->name('api.get.user')->where('id', '[0-9]+');
    Route::get('/users/name/{name}', [App\Http\Controllers\Auth\UserController::class, 'getUserByName'])->name('api.get.user_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/users', [App\Http\Controllers\Auth\UserController::class, 'createUsers'])->name('api.post.users');
    Route::put('/users/{id}', [App\Http\Controllers\Auth\UserController::class, 'updateUsers'])->name('api.put.users')->where('id', '[0-9]+');
    Route::put('/users/password/{id}', [App\Http\Controllers\Auth\UserController::class, 'updatePassword'])->name('api.put.password')->where('id', '[0-9]+');
    Route::delete('/users/{id}', [App\Http\Controllers\Auth\UserController::class, 'deleteUsers'])->name('api.delete.users')->where('id', '[0-9]+');

    /**
     * =======================================================
     * SEREPKÖRÖK (ROLES)
     * =======================================================
     */
    Route::get('/roles', [\App\Http\Controllers\Auth\RoleController::class, 'getRoles'])->name('api.get.roles');
    Route::get('/roles/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'getRole'])->name('api.get.role')->where('id', '[0-9]+');
    Route::get('/roles/name/{name}', [\App\Http\Controllers\Auth\RoleController::class, 'getRolesByName'])->name('api.get.role_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/roles', [\App\Http\Controllers\Auth\RoleController::class, 'createRole'])->name('api.post.roles');
    Route::put('/roles/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'updateRoles'])->name('api.put.roles')->where('id', '[0-9]+');
    Route::delete('/roles/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'deleteRoles'])->name('api.delete.roles')->where('id', '[0-9]+');

    /**
     * =======================================================
     * ENGEDÉLYEK (PERMISSIONS)
     * =======================================================
     */
    Route::get('/permissions', [App\Http\Controllers\Auth\PermissionController::class, 'getPermissions'])->name('api.get.permissions');
    Route::get('/permissions/{id}', [App\Http\Controllers\Auth\PermissionController::class, 'getPermission'])->name('api.get.permission')->where('id', '[0-9]+');
    Route::get('/permissions/name/{name}', [App\Http\Controllers\Auth\PermissionController::class, 'getPermissionByName'])->name('api.get.permission_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/permissions', [App\Http\Controllers\Auth\PermissionController::class, 'createPermissions'])->name('api.post.permissions');
    Route::put('/permissions/{id}', [App\Http\Controllers\Auth\PermissionController::class, 'updatePermissions'])->name('api.put.permissions')->where('id', '[0-9]+');
    Route::delete('/permissions/{id}', [App\Http\Controllers\Auth\PermissionController::class, 'deletePermissions'])->name('api.delete.permissions')->where('id', '[0-9]+');

    /**
     * =======================================================
     * SUBDOMAINS
     * =======================================================
     */
    Route::get('/subdomains', [\App\Http\Controllers\SubdomainController::class, 'getSubdomains'])->name('api.get.subdomains');
    Route::get('/subdomains/{id}', [\App\Http\Controllers\SubdomainController::class, 'getSubdomain'])->name('api.get.subdomains')->where('id', '[0-9]+');
    Route::get('/subdomains/name/{name}', [\App\Http\Controllers\SubdomainController::class, 'getSubdomainByName'])->name('api.get.subdomain_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/subdomains', [\App\Http\Controllers\SubdomainController::class, 'createSubdomains'])->name('api.post.subdomains');
    Route::put('/subdomains/{id}', [\App\Http\Controllers\SubdomainController::class, 'updateSubdomains'])->name('api.put.subdomains')->where('id', '[0-9]+');
    Route::delete('/subdomains/{id}', [\App\Http\Controllers\SubdomainController::class, 'deleteSubdomains'])->name('api.delete.subdomains')->where('id', '[0-9]+');

    /**
     * =======================================================
     * SUBDOMAIN STATES
     * =======================================================
     */
    Route::get('/subdomain_states', [App\Http\Controllers\SubdomainStateController::class, 'getSubdomainStates'])->name('api.get.subdomain_states');
    Route::get('/subdomain_states/{id}', [App\Http\Controllers\SubdomainStateController::class, 'getSubdomainState'])->name('api.get.subdomain_state')->where('id', '[0-9]+');
    Route::get('/subdomain_states/name/{name}', [App\Http\Controllers\SubdomainStateController::class, 'getSubdomainStateByName'])->name('api.get.subdomain_state_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/subdomain_states', [App\Http\Controllers\SubdomainStateController::class, 'createSubdomainStates'])->name('api.post.subdomain_states');
    Route::put('/subdomain_states/{id}', [App\Http\Controllers\SubdomainStateController::class, 'updateSubdomainState'])->name('api.put.subdomain_states')->where('id', '[0-9]+');
    Route::delete('/subdomain_states/{id}', [App\Http\Controllers\SubdomainStateController::class, 'deleteSubdomainStates'])->name('api.delete.subdomain_states')->where('id', '[0-9]+');

    /**
     * =======================================================
     * PERSONS
     * =======================================================
     */
    Route::get('/persons', [\App\Http\Controllers\PersonController::class, 'getPersons'])->name('api.get.persons');
    Route::get('/persons/{id}', [\App\Http\Controllers\PersonController::class, 'getPerson'])->name('api.get.person')->where('id', '[0-9]+');
    Route::get('/persons/name/{name}', [\App\Http\Controllers\PersonController::class, 'getPersonByName'])->name('api.get.person_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/persons', [\App\Http\Controllers\PersonController::class, 'createPersons'])->name('api.post.persons');
    Route::put('/persons/{id}', [\App\Http\Controllers\PersonController::class, 'updatePersons'])->name('api.put.persons')->where('id', '[0-9]+');
    Route::delete('/persons/{id}', [\App\Http\Controllers\PersonController::class, 'deletePersons'])->name('api.delete.persons')->where('id', '[0-9]+');

    /**
     * =======================================================
     * ENTITIES
     * =======================================================
     */
    Route::get('/entities', [\App\Http\Controllers\EntityController::class, 'getEntities'])->name('api.get.entities');
    Route::get('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'getEntity'])->name('api.get.entity')->where('id', '[0-9]+');
    Route::get('/entities/name/{name}', [\App\Http\Controllers\EntityController::class, 'getEntityByName'])->name('api.get.entity_by_name')->where('name', '[a-zA-Z0-9\s]{3,}');
    Route::post('/entities', [\App\Http\Controllers\EntityController::class, 'createEntities'])->name('api.post.entities');
    Route::put('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'updateEntities'])->name('api.put.entities')->where('id', '[0-9]+');
    Route::delete('/entities/{id}', [\App\Http\Controllers\EntityController::class, 'deleteEntities'])->name('api.delete.entities')->where('id', '[0-9]+');
    
    /**
     * =======================================================
     * HIERARCHIA
     * =======================================================
     */
    // Szülö hozzáadása
    Route::post('/entity-hierarchy/{childId}/add-parent', [\App\Http\Controllers\HierarchyController::class, 'addParent']);
    // Gyermek hozzáadása
    Route::post('/entity-hierarchy/{parentId}/add-child', [\App\Http\Controllers\HierarchyController::class, 'addChild'])->name('addChild');
    // Hierarchia lekérdezése
    Route::get('/entity-hierarchy/{entityId}/hierarchy', [\App\Http\Controllers\HierarchyController::class, 'getHierarchy']);
    // Gyermek eltávolítása
    Route::delete('/entity-hierarchy/{parentId}/remove-child', [\App\Http\Controllers\HierarchyController::class, 'removeChild']);
    // Nagyfőnök lekérése
    Route::get('/entity-hierarchy/big-bosses', [\App\Http\Controllers\HierarchyController::class, 'getBigBosses']);
    // Hierarchia épségének ellenőrzése
    Route::get('/entity-hierarchy/validate', [\App\Http\Controllers\HierarchyController::class, 'validateHierarchy']);
    // Izolált entitások keresése
    Route::get('/entity-hierarchy/isolated-entities', [\App\Http\Controllers\HierarchyController::class, 'checkIsolatedEntities']);
    // Egy vezető beosztottjainak áthelyezése egy másik vezető alá
    Route::post('/entity-hierarchy/{fromManagerId}/transfer-subordinates', [\App\Http\Controllers\HierarchyController::class, 'transferSubordinates']);
    // Két vezető beosztottjainak kicserélése
    Route::post('/entity-hierarchy/swap-subordinates', [\App\Http\Controllers\HierarchyController::class, 'swapSubordinates']);
    // Dolgozói szint megállapítása
    Route::get('/employee/{id}/role', [\App\Http\Controllers\HierarchyController::class, 'getEmployeeRole']);

});
