<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('test-me', function () {
    return 'The application is working correctly.';
});

Route::group(['middleware' => 'web'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
