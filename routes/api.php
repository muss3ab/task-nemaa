<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

// Users routes
Route::prefix('v1')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
