<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Infrastructure\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Infrastructure\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/articles', [\App\Infrastructure\Http\Controllers\ArticlesController::class, 'index'])->name('articles.index');
Route::get('/articles/rss', [\App\Infrastructure\Http\Controllers\ArticlesRssController::class, 'index'])->name('articles.rss');
Route::get('/articles/{uuid}-{slug}', [\App\Infrastructure\Http\Controllers\ArticlesController::class, 'show'])->name('articles.show');

Route::get('/sitemap.xml', [\App\Infrastructure\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/images/{filename}', [\App\Infrastructure\Http\Controllers\ImagesController::class, 'show'])->name('images');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [\App\Infrastructure\Http\Controllers\LoginController::class, 'form'])->name('login');
    Route::post('/login', [\App\Infrastructure\Http\Controllers\LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [\App\Infrastructure\Http\Controllers\LoginController::class, 'logout'])->name('logout.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [\App\Application\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/articles', [\App\Infrastructure\Http\Controllers\Admin\ArticlesController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/articles/create', [\App\Infrastructure\Http\Controllers\Admin\ArticlesController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/articles', [\App\Infrastructure\Http\Controllers\Admin\ArticlesController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/articles/{uuid}/edit', [\App\Infrastructure\Http\Controllers\Admin\ArticlesController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/admin/articles/{uuid}', [\App\Infrastructure\Http\Controllers\Admin\ArticlesController::class, 'update'])->name('admin.articles.update');
});
