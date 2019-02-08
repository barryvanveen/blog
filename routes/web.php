<?php

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/projects', [\App\Http\Controllers\ProjectsController::class, 'index'])->name('projects');
Route::get('/rss', [\App\Http\Controllers\AboutController::class, 'index'])->name('rss');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
