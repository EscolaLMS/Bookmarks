<?php

use EscolaLms\Bookmarks\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/bookmarks')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::post(null, [BookmarkController::class, 'create']);
        Route::patch('{id}', [BookmarkController::class, 'update']);
        Route::delete('{id}', [BookmarkController::class, 'delete']);
        Route::get('', [BookmarkController::class, 'findAll']);
    });
