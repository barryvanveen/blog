<?php

Route::get('/', [\App\Infrastructure\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Infrastructure\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/articles', [\App\Infrastructure\Http\Controllers\ArticlesController::class, 'index'])->name('articles.index');
Route::get('/articles/create', [\App\Infrastructure\Http\Controllers\ArticlesController::class, 'store'])->name('articles.store');
Route::get('/projects', [\App\Infrastructure\Http\Controllers\ProjectsController::class, 'index'])->name('projects');
Route::get('/rss', [\App\Infrastructure\Http\Controllers\AboutController::class, 'index'])->name('rss');
Route::get('/sitemap.xml', [\App\Infrastructure\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
