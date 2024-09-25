<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::controller(UserController::class)->prefix('v1')->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

Route::group( ['middleware' => ['auth:sanctum']], function() {
    Route::controller(UserController::class)->group(function () {
        Route::get('logout', 'logout')->name('logout');
    });
});
