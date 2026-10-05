<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;

// Home / Dashboard
Route::get('/', [BillingController::class, 'index'])->name('home');
Route::get('/home', [BillingController::class, 'index'])->name('home.index');
Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');

// Reports (Matching Angkasa Pura PABX System)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/division-summary', [BillingController::class, 'divisionSummary'])->name('division-summary');
    Route::get('/favourite-area', [BillingController::class, 'favouriteArea'])->name('favourite-area');
    Route::get('/favourite-business', [BillingController::class, 'favouriteBusiness'])->name('favourite-business');
    Route::get('/peak-time', [BillingController::class, 'peakTime'])->name('peak-time');
    Route::get('/personal-favorite-dialed', [BillingController::class, 'personalFavoriteDialed'])->name('personal-favorite-dialed');
});

// Database Relations & Utilities
Route::get('/schema', [BillingController::class, 'schema'])->name('billing.schema');
Route::post('/billing/simulate', [BillingController::class, 'simulate'])->name('billing.simulate');
Route::post('/billing/store', [BillingController::class, 'storeCall'])->name('billing.store');
