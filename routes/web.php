<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
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
    
    Route::get(
        '/getCompanies', 
        [CompanyController::class, 'getCompanies']
    )->name('getCompanies');
    
    //Route::resource('companies', App\Http\Controllers\CompanyController::class);
    
    Route::resource('companies', App\Http\Controllers\CompanyController::class)->names([
        'index' => 'companies',
        'create' => 'companies.create',
        'store' => 'companies.store',
        'update' => 'companies.update',
        'destroy' => 'companies.destroy',
    ]);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
