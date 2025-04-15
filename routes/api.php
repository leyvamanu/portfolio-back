<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/hello', function () {
    return 'Hello World';
});

Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:5,1');

Route::get('/reviews', [ReviewController::class, 'index']);
