<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\InformationController;
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

// Information (Angkasa Pura PABX System)
Route::prefix('information')->name('information.')->group(function () {
    Route::get('/call-history', [InformationController::class, 'callHistory'])->name('call-history');
    Route::get('/recalculating-call', [InformationController::class, 'recalculatingCall'])->name('recalculating-call');
    Route::get('/monitoring-port', [InformationController::class, 'monitoringPort'])->name('monitoring-port');
});

// Reports (Matching Angkasa Pura PABX System)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/division-summary', [BillingController::class, 'divisionSummary'])->name('division-summary');
    Route::get('/favourite-area', [BillingController::class, 'favouriteArea'])->name('favourite-area');
    Route::get('/favourite-business', [BillingController::class, 'favouriteBusiness'])->name('favourite-business');
    Route::get('/peak-time', [BillingController::class, 'peakTime'])->name('peak-time');
    Route::get('/personal-detail', [BillingController::class, 'personalDetail'])->name('personal-detail');
    Route::get('/personal-favorite-area', [BillingController::class, 'personalFavoriteArea'])->name('personal-favorite-area');
    Route::get('/personal-favorite-dialed', [BillingController::class, 'personalFavoriteDialed'])->name('personal-favorite-dialed');
    Route::get('/personal-summary', [BillingController::class, 'personalSummary'])->name('personal-summary');
    Route::get('/phone-id-detail', [BillingController::class, 'phoneIdDetail'])->name('phone-id-detail');
    Route::get('/phone-id-summary', [BillingController::class, 'phoneIdSummary'])->name('phone-id-summary');
    Route::get('/unit-summary', [BillingController::class, 'unitSummary'])->name('unit-summary');
});

// Database Relations & Utilities
Route::get('/schema', [BillingController::class, 'schema'])->name('billing.schema');
Route::post('/billing/simulate', [BillingController::class, 'simulate'])->name('billing.simulate');
Route::post('/billing/store', [BillingController::class, 'storeCall'])->name('billing.store');
Route::post('/billing/recalculate', [BillingController::class, 'recalculate'])->name('billing.recalculate');

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
    Route::post('/phone-code', [SettingController::class, 'storePhoneCode'])->name('phone-code.store');
    Route::put('/phone-code/{id}', [SettingController::class, 'updatePhoneCode'])->name('phone-code.update');
    Route::delete('/phone-code/{id}', [SettingController::class, 'destroyPhoneCode'])->name('phone-code.destroy');

    Route::get('/rate', [SettingController::class, 'masterRate'])->name('rate');
    Route::post('/rate', [SettingController::class, 'storeMasterRate'])->name('rate.store');
    Route::put('/rate/{id}', [SettingController::class, 'updateMasterRate'])->name('rate.update');
    Route::delete('/rate/{id}', [SettingController::class, 'destroyMasterRate'])->name('rate.destroy');

    Route::get('/special-rate', [SettingController::class, 'masterSpecialRate'])->name('special-rate');
    Route::post('/special-rate', [SettingController::class, 'storeMasterSpecialRate'])->name('special-rate.store');
    Route::put('/special-rate/{id}', [SettingController::class, 'updateMasterSpecialRate'])->name('special-rate.update');
    Route::delete('/special-rate/{id}', [SettingController::class, 'destroyMasterSpecialRate'])->name('special-rate.destroy');

    Route::get('/user', [SettingController::class, 'masterUser'])->name('user');
    Route::post('/user', [SettingController::class, 'storeMasterUser'])->name('user.store');
    Route::put('/user/{id}', [SettingController::class, 'updateMasterUser'])->name('user.update');
    Route::delete('/user/{id}', [SettingController::class, 'destroyMasterUser'])->name('user.destroy');

    Route::get('/prefix', [SettingController::class, 'prefixCode'])->name('prefix');
    Route::post('/prefix', [SettingController::class, 'storePrefixCode'])->name('prefix.store');
    Route::put('/prefix/{id}', [SettingController::class, 'updatePrefixCode'])->name('prefix.update');
    Route::delete('/prefix/{id}', [SettingController::class, 'destroyPrefixCode'])->name('prefix.destroy');
});

// Utility (Server Health, Backup & Maintenance, Run Service, Level Akses)
Route::prefix('utility')->name('utility.')->group(function () {
    Route::get('/server-health', [\App\Http\Controllers\UtilityController::class, 'serverHealth'])->name('server-health');
    Route::get('/backup-maintenance', [\App\Http\Controllers\UtilityController::class, 'backupMaintenance'])->name('backup-maintenance');
    Route::post('/backup/generate', [\App\Http\Controllers\UtilityController::class, 'generateBackup'])->name('backup.generate');
    Route::post('/backup/optimize', [\App\Http\Controllers\UtilityController::class, 'optimizeDatabase'])->name('backup.optimize');
    Route::post('/backup/clear-cache', [\App\Http\Controllers\UtilityController::class, 'clearCache'])->name('backup.clear-cache');
    Route::get('/run-service', [\App\Http\Controllers\UtilityController::class, 'runService'])->name('run-service');
    Route::get('/level-akses', [\App\Http\Controllers\UtilityController::class, 'levelAkses'])->name('level-akses');
    Route::post('/level-akses', [\App\Http\Controllers\UtilityController::class, 'updateLevelAkses'])->name('level-akses.update');
});

