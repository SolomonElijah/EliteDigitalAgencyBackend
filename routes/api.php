<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\ContactApiController;

Route::prefix('v1')->group(function () {

    // Portfolio Projects
    Route::get('/projects', [ProjectApiController::class, 'index']);
    Route::get('/projects/featured', [ProjectApiController::class, 'featured']);
    Route::get('/projects/categories', [ProjectApiController::class, 'categories']);
    Route::get('/projects/{slug}', [ProjectApiController::class, 'show']);

    // Contact Form
    Route::post('/contact', [ContactApiController::class, 'store']);

});