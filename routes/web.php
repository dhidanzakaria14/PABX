<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BillingController::class, 'index'])->name('billing.index');
Route::get('/billing', [BillingController::class, 'index']);
Route::post('/billing/simulate', [BillingController::class, 'simulate'])->name('billing.simulate');
Route::post('/billing/store', [BillingController::class, 'storeCall'])->name('billing.store');
Route::get('/schema', [BillingController::class, 'schema'])->name('billing.schema');
