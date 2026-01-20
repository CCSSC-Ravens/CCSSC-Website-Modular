<?php

use App\Modules\Committee\Http\Controllers\CommitteePageController;
use Illuminate\Support\Facades\Route;

Route::get('/departments', [CommitteePageController::class, 'index'])->name('committee.index');
