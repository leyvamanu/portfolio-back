<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:5,1');
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/featured-projects', [ProjectController::class, 'featuredProjects']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);
