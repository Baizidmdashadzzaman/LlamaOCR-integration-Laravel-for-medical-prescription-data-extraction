<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\OCRController;

Route::post('/ocr', [OCRController::class, 'extractText']);
