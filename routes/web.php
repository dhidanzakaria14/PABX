<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Authentication (Login / Logout / Forgot Password)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');
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

// Settings (Matching Angkasa Pura PABX System)
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/admin', [SettingController::class, 'admin'])->name('admin');
    Route::post('/admin', [SettingController::class, 'storeAdmin'])->name('admin.store');
    Route::put('/admin/{id}', [SettingController::class, 'updateAdmin'])->name('admin.update');
    Route::delete('/admin/{id}', [SettingController::class, 'destroyAdmin'])->name('admin.destroy');

    Route::get('/business-phone', [SettingController::class, 'businessPhone'])->name('business-phone');
    Route::post('/business-phone', [SettingController::class, 'storeBusinessPhone'])->name('business-phone.store');
    Route::put('/business-phone/{id}', [SettingController::class, 'updateBusinessPhone'])->name('business-phone.update');
    Route::delete('/business-phone/{id}', [SettingController::class, 'destroyBusinessPhone'])->name('business-phone.destroy');

    Route::get('/department-group', [SettingController::class, 'departmentGroup'])->name('department-group');
    Route::post('/department-group', [SettingController::class, 'storeDepartmentGroup'])->name('department-group.store');
    Route::put('/department-group/{id}', [SettingController::class, 'updateDepartmentGroup'])->name('department-group.update');
    Route::delete('/department-group/{id}', [SettingController::class, 'destroyDepartmentGroup'])->name('department-group.destroy');

    Route::get('/phone-code', [SettingController::class, 'phoneCode'])->name('phone-code');
    Route::get('/rate', [SettingController::class, 'masterRate'])->name('rate');
    Route::get('/special-rate', [SettingController::class, 'masterSpecialRate'])->name('special-rate');
    Route::get('/user', [SettingController::class, 'masterUser'])->name('user');
    Route::get('/prefix', [SettingController::class, 'prefixCode'])->name('prefix');
});

