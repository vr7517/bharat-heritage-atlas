<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\InscriptionController;
use App\Http\Controllers\Web\MapController;
use App\Http\Controllers\Web\ObjectController;
use App\Http\Controllers\Web\SiteController;
use App\Http\Controllers\Web\TimelineController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');

Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
Route::get('/sites/{slug}', [SiteController::class, 'show'])->name('sites.show');

Route::get('/objects', [ObjectController::class, 'index'])->name('objects.index');
Route::get('/objects/{slug}', [ObjectController::class, 'show'])->name('objects.show');

Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::get('/inscriptions/{slug}', [InscriptionController::class, 'show'])->name('inscriptions.show');
