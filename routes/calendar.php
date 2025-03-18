<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    
    /**
     * =====================================================
     * CALENDAR
     * =====================================================
     */
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
    
    /**
     * =====================================================
     * ENTITY CALENDAR
     * =====================================================
     */
    Route::get('/entity_calendar', [\App\Http\Controllers\Entity\EntityCalendarController::class, 'index'])->name('entity_calendar');
});
