<?php

use App\Http\Controllers\Api\V1\InscriptionController;
use App\Http\Controllers\Api\V1\ObjectController;
use App\Http\Controllers\Api\V1\SiteController;
use App\Http\Controllers\Api\V1\SourceController;
use App\Http\Controllers\Api\V1\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Heritage Sites
    Route::get('/sites', [SiteController::class, 'index'])->name('api.v1.sites.index');
    Route::get('/sites/{slug}', [SiteController::class, 'show'])->name('api.v1.sites.show');

    // Heritage Objects
    Route::get('/objects', [ObjectController::class, 'index'])->name('api.v1.objects.index');
    Route::get('/objects/{slug}', [ObjectController::class, 'show'])->name('api.v1.objects.show');

    // Epigraphy & Inscriptions
    Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('api.v1.inscriptions.index');
    Route::get('/inscriptions/{slug}', [InscriptionController::class, 'show'])->name('api.v1.inscriptions.show');

    // Bibliographic Sources Repository
    Route::get('/sources', [SourceController::class, 'index'])->name('api.v1.sources.index');
    Route::get('/sources/{id}', [SourceController::class, 'show'])->name('api.v1.sources.show');

    // Evidence Verification Engine
    Route::get('/claims/{id}', [VerificationController::class, 'showClaim'])->name('api.v1.claims.show');
    Route::get('/evidence/{id}', [VerificationController::class, 'showEvidence'])->name('api.v1.evidence.show');
});
