<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Authentication (Login / Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/action_log.php', [AuthController::class, 'logout']); // Legacy logout URL compatibility

// Profile (Account Profile - View & Edit)
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/account/profile', [ProfileController::class, 'show'])->name('profile.account');

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
    Route::get('/personal-summary', [BillingController::class, 'personalSummary'])->name('personal-summary');
});

// Database Relations & Utilities
Route::get('/schema', [BillingController::class, 'schema'])->name('billing.schema');
Route::post('/billing/simulate', [BillingController::class, 'simulate'])->name('billing.simulate');
Route::post('/billing/store', [BillingController::class, 'storeCall'])->name('billing.store');
