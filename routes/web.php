<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ClaimController as AdminClaimController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DatasetController as AdminDatasetController;
use App\Http\Controllers\Admin\DynastyController as AdminDynastyController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\InscriptionController as AdminInscriptionController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\ObjectController as AdminObjectController;
use App\Http\Controllers\Admin\PeriodController as AdminPeriodController;
use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
use App\Http\Controllers\Api\V1\ExportController;
use App\Http\Controllers\Web\DatasetController;
use App\Http\Controllers\Web\DeveloperController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InscriptionController;
use App\Http\Controllers\Web\MapController;
use App\Http\Controllers\Web\ObjectController;
use App\Http\Controllers\Web\ResearchToolsController;
use App\Http\Controllers\Web\SearchController;
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
// Language & Locale Switcher (Persists in Session & 1-Year Cookie)
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'hi'], true)) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('bha_locale', $locale, 60 * 24 * 365));
    }

    return redirect()->back();
})->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');

Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
Route::get('/sites/{slug}', [SiteController::class, 'show'])->name('sites.show');

Route::get('/objects', [ObjectController::class, 'index'])->name('objects.index');
Route::get('/objects/{slug}', [ObjectController::class, 'show'])->name('objects.show');

Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::get('/inscriptions/{slug}', [InscriptionController::class, 'show'])->name('inscriptions.show');

Route::get('/datasets', [DatasetController::class, 'index'])->name('datasets.index');
Route::get('/datasets/{slug}', [DatasetController::class, 'show'])->name('datasets.show');
Route::get('/datasets/{slug}/download', [DatasetController::class, 'download'])->name('datasets.download');

Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
Route::get('/verification/claims/{id}', [VerificationController::class, 'show'])->name('verification.show');

Route::get('/sources', [SourceController::class, 'index'])->name('sources.index');
Route::get('/sources/{id}', [SourceController::class, 'show'])->name('sources.show');
Route::get('/sources/export/bibtex', [ExportController::class, 'bibtex'])->name('sources.export.bibtex');

// Academic Research Workbench & Tools
Route::get('/research-tools', [ResearchToolsController::class, 'index'])->name('research.index');

// Public REST API & Developer Hub
Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');

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

        // Open Datasets Management (CRUD, Publishing & Featured)
        Route::resource('datasets', AdminDatasetController::class);
        Route::post('datasets/{id}/toggle-publish', [AdminDatasetController::class, 'togglePublish'])->name('datasets.toggle-publish');
        Route::post('datasets/{id}/toggle-featured', [AdminDatasetController::class, 'toggleFeatured'])->name('datasets.toggle-featured');

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

        // Admin Data Export Center
        Route::get('exports', [AdminExportController::class, 'index'])->name('exports.index');
        Route::get('exports/{entity}/{format}', [AdminExportController::class, 'download'])->name('exports.download');
    });
});
