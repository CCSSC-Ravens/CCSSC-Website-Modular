<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::any('{any?}', function (Request $request) {
        if (config('admin.locked_notice_mode', '404') === 'message') {
            return response(config('admin.locked_notice', 'This page does not exist.'), 404);
        }
        abort(404);
    })->where('any', '.*');
});
