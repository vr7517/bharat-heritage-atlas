<?php

use App\Http\Controllers\Api\V1\ObjectController;
use App\Http\Controllers\Api\V1\SiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Heritage Sites
    Route::get('/sites', [SiteController::class, 'index'])->name('api.v1.sites.index');
    Route::get('/sites/{slug}', [SiteController::class, 'show'])->name('api.v1.sites.show');

    // Heritage Objects
    Route::get('/objects', [ObjectController::class, 'index'])->name('api.v1.objects.index');
    Route::get('/objects/{slug}', [ObjectController::class, 'show'])->name('api.v1.objects.show');
});
