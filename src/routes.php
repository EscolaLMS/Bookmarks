<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/bookmarks')
    ->middleware(['auth:api'])
    ->group(function () {
    });
