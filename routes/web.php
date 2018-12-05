<?php

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
