<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });


/**
 * A cégek listájának lekérése az API-n keresztül.
 *
 * @param CompanyController $companyController A cégvezérlő példány.
 * @return \Illuminate\Http\JsonResponse A vállalatok listáját tartalmazó JSON-válasz.
 */
Route::get('/items', [CompanyController::class, 'getCompanies'])->name('api.get.companies');


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