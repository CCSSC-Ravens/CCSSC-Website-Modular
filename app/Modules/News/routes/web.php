<?php

use App\Modules\News\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/newsPage', [NewsController::class, 'index'])->name('news.index');


