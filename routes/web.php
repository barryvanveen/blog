<?php

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/articles', [\App\Http\Controllers\ArticlesController::class, 'index'])->name('articles.index');
Route::get('/articles/create', [\App\Http\Controllers\ArticlesController::class, 'store'])->name('articles.store');
Route::get('/projects', [\App\Http\Controllers\ProjectsController::class, 'index'])->name('projects');
Route::get('/rss', [\App\Http\Controllers\AboutController::class, 'index'])->name('rss');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
