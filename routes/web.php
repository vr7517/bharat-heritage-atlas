<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ClaimController as AdminClaimController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DynastyController as AdminDynastyController;
use App\Http\Controllers\Admin\InscriptionController as AdminInscriptionController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\ObjectController as AdminObjectController;
use App\Http\Controllers\Admin\PeriodController as AdminPeriodController;
use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
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

        // Artifacts & Sculptures Management (CRUD & Publishing)
        Route::resource('objects', AdminObjectController::class);
        Route::post('objects/{id}/toggle-publish', [AdminObjectController::class, 'togglePublish'])->name('objects.toggle-publish');

        // Epigraphy & Inscriptions Management (CRUD & Publishing)
        Route::resource('inscriptions', AdminInscriptionController::class);
        Route::post('inscriptions/{id}/toggle-publish', [AdminInscriptionController::class, 'togglePublish'])->name('inscriptions.toggle-publish');

        // Dynasties Management (CRUD)
        Route::resource('dynasties', AdminDynastyController::class);

        // Timeline Historical Periods Management (CRUD)
        Route::resource('periods', AdminPeriodController::class);

        // Media & Visual Archive Pipeline
        Route::post('media/upload', [AdminMediaController::class, 'store'])->name('media.upload');
        Route::post('media/{id}/cover', [AdminMediaController::class, 'setCover'])->name('media.set-cover');
        Route::delete('media/{id}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        // Bibliographic Source Management (CRUD)
        Route::resource('sources', AdminSourceController::class);

        // Claims & Evidence Network Engine
        Route::post('claims', [AdminClaimController::class, 'store'])->name('claims.store');
        Route::put('claims/{id}', [AdminClaimController::class, 'update'])->name('claims.update');
        Route::delete('claims/{id}', [AdminClaimController::class, 'destroy'])->name('claims.destroy');
        Route::post('claims/{id}/evidence', [AdminClaimController::class, 'attachEvidence'])->name('claims.evidence.attach');
        Route::delete('claims/{claimId}/evidence/{evidenceId}', [AdminClaimController::class, 'detachEvidence'])->name('claims.evidence.detach');
    });
});
