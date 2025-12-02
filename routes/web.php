<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

// 1. GET route to show the input form
Route::get('/', [ReviewController::class, 'showForm'])->name('review.form');

// 2. POST route to process the text and show results
Route::post('/generate', [ReviewController::class, 'generateReview'])->name('review.generate');
