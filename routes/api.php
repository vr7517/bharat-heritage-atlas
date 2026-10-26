<?php

use App\Http\Controllers\Api\V1\DatasetApiController;
use App\Http\Controllers\Api\V1\ExportController;
use App\Http\Controllers\Api\V1\GeoController;
use App\Http\Controllers\Api\V1\InscriptionController;
use App\Http\Controllers\Api\V1\ObjectController;
use App\Http\Controllers\Api\V1\SearchApiController;
use App\Http\Controllers\Api\V1\SiteController;
use App\Http\Controllers\Api\V1\SourceController;
use App\Http\Controllers\Api\V1\TimelineController;
use App\Http\Controllers\Api\V1\VerificationController;
use App\Http\Controllers\Web\ResearchToolsController;
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

    // Open Research Datasets
    Route::get('/datasets', [DatasetApiController::class, 'index'])->name('api.v1.datasets.index');
    Route::get('/datasets/{slug}', [DatasetApiController::class, 'show'])->name('api.v1.datasets.show');

    // Bibliographic Sources Repository
    Route::get('/sources', [SourceController::class, 'index'])->name('api.v1.sources.index');
    Route::get('/sources/{id}', [SourceController::class, 'show'])->name('api.v1.sources.show');

    // Evidence Verification Engine
    Route::get('/claims/{id}', [VerificationController::class, 'showClaim'])->name('api.v1.claims.show');
    Route::get('/evidence/{id}', [VerificationController::class, 'showEvidence'])->name('api.v1.evidence.show');

    // Chronological Timeline Stream
    Route::get('/timeline', [TimelineController::class, 'index'])->name('api.v1.timeline.index');

    // Geospatial GeoJSON Map Endpoints
    Route::get('/geo/sites', [GeoController::class, 'sites'])->name('api.v1.geo.sites');

    // Cross-Corpus Programmatic Search
    Route::get('/search', [SearchApiController::class, 'index'])->name('api.v1.search');

    // Academic Data Exports (GeoJSON, BibTeX, Tabular CSV)
    Route::get('/export/geojson', [ExportController::class, 'geojson'])->name('api.v1.export.geojson');
    Route::get('/export/bibtex', [ExportController::class, 'bibtex'])->name('api.v1.export.bibtex');
    Route::get('/export/sites.csv', [ExportController::class, 'sitesCsv'])->name('api.v1.export.sites.csv');
    Route::get('/export/inscriptions.csv', [ExportController::class, 'inscriptionsCsv'])->name('api.v1.export.inscriptions.csv');
    Route::get('/export/objects.csv', [ExportController::class, 'objectsCsv'])->name('api.v1.export.objects.csv');

    // Research Workbench Tools API
    Route::get('/research/citation', [ResearchToolsController::class, 'generateCitation'])->name('api.v1.research.citation');
});
