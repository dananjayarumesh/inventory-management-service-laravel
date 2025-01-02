<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DispatchNoteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReceiveNoteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\HasRole;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->group(function () {
        Route::middleware('auth:api')
            ->group(function () {
                Route::resource('items', ItemController::class);
                Route::resource('categories', CategoryController::class);
                Route::resource('dispatch-notes', DispatchNoteController::class);
                Route::resource('receive-notes', ReceiveNoteController::class);
                Route::resource('users', UserController::class)->middleware(HasRole::class . ':admin');
            });

        Route::prefix('auth')
            ->group(function () {
                Route::post('login', [AuthController::class, 'login']);
                Route::post('refresh', [AuthController::class, 'refresh']);
            });
    });
