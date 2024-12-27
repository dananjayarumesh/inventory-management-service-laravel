<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DispatchNoteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecveiveNoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->group(function () {
        Route::middleware('auth:api')
            ->group(function () {
                Route::resource('items', ItemController::class);
                Route::resource('categories', CategoryController::class);
                Route::resource('dispatch-notes', DispatchNoteController::class);
                Route::resource('receive-notes', RecveiveNoteController::class);
            });
    });
