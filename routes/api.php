<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/items', function(){ \Log::info('API/GET'); });

Route::get(
    '/items', 
    [
        CompanyController::class, 
        'getCompanies'
    ]
);

Route::post('/items', [CompanyController::class, 'createCompany']);
Route::put('/items/{id}', [CompanyController::class, 'updateCompany']);

//Route::post('/items', function(){
//    \Log::info('API/POST');
//});

//Route::put('/items', function(){
//    \Log::info('API/PUT');
//});

Route::delete('/items', function(){
    \Log::info('API/DELETE');
});