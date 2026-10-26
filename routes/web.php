<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InscriptionController;
use App\Http\Controllers\Web\MapController;
use App\Http\Controllers\Web\ObjectController;
use App\Http\Controllers\Web\SiteController;
use App\Http\Controllers\Web\SourceController;
use App\Http\Controllers\Web\TimelineController;
use App\Http\Controllers\Web\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes (Public Digital Atlas)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');

Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
Route::get('/sites/{slug}', [SiteController::class, 'show'])->name('sites.show');

Route::get('/objects', [ObjectController::class, 'index'])->name('objects.index');
Route::get('/objects/{slug}', [ObjectController::class, 'show'])->name('objects.show');

Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::get('/inscriptions/{slug}', [InscriptionController::class, 'show'])->name('inscriptions.show');

Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
Route::get('/verification/claims/{id}', [VerificationController::class, 'show'])->name('verification.show');

Route::get('/sources', [SourceController::class, 'index'])->name('sources.index');
Route::get('/sources/{id}', [SourceController::class, 'show'])->name('sources.show');

/*
|--------------------------------------------------------------------------
| Administrative CMS Routes (Protected by EnsureAdmin middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Authentication Routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Heritage Site Management (CRUD & Publishing)
        Route::resource('sites', AdminSiteController::class);
        Route::post('sites/{id}/toggle-publish', [AdminSiteController::class, 'togglePublish'])->name('sites.toggle-publish');
    });
});
