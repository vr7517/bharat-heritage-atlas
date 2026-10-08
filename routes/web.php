<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
