<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['cache'])->group(function () {
    Route::get('/', [\App\Application\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/about', [\App\Application\Http\Controllers\AboutController::class, 'index'])->name('about');
    Route::get('/about/books', [\App\Application\Http\Controllers\BooksController::class, 'index'])->name('books');
    Route::get('/articles', [\App\Application\Http\Controllers\ArticlesController::class, 'index'])->name('articles.index');
    Route::get('/articles/rss', [\App\Application\Http\Controllers\ArticlesRssController::class, 'index'])->name('articles.rss');
    Route::get('/articles/{uuid}-{slug}', [\App\Application\Http\Controllers\ArticlesController::class, 'show'])->name('articles.show');

    Route::get('/sitemap.xml', [\App\Application\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
    Route::get('/images/{filename}', [\App\Application\Http\Controllers\ImagesController::class, 'show'])->name('images');
});

Route::get('/csrf-token', [\App\Application\Http\Controllers\CsrfController::class, 'csrf'])->name('csrf');

Route::get('/login', [\App\Application\Http\Controllers\LoginController::class, 'form'])->name('login');
Route::post('/login', [\App\Application\Http\Controllers\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Application\Http\Controllers\LoginController::class, 'logout'])->name('logout.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [\App\Application\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/articles', [\App\Application\Http\Controllers\Admin\ArticlesController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/articles/create', [\App\Application\Http\Controllers\Admin\ArticlesController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/articles', [\App\Application\Http\Controllers\Admin\ArticlesController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/articles/{uuid}/edit', [\App\Application\Http\Controllers\Admin\ArticlesController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/admin/articles/{uuid}', [\App\Application\Http\Controllers\Admin\ArticlesController::class, 'update'])->name('admin.articles.update');

    Route::get('/admin/comments', [\App\Application\Http\Controllers\Admin\CommentsController::class, 'index'])->name('admin.comments.index');
    Route::get('/admin/comments/create', [\App\Application\Http\Controllers\Admin\CommentsController::class, 'create'])->name('admin.comments.create');
    Route::post('/admin/comments', [\App\Application\Http\Controllers\Admin\CommentsController::class, 'store'])->name('admin.comments.store');
    Route::get('/admin/comments/{uuid}/edit', [\App\Application\Http\Controllers\Admin\CommentsController::class, 'edit'])->name('admin.comments.edit');
    Route::put('/admin/comments/{uuid}', [\App\Application\Http\Controllers\Admin\CommentsController::class, 'update'])->name('admin.comments.update');

    Route::get('/admin/pages', [\App\Application\Http\Controllers\Admin\PagesController::class, 'index'])->name('admin.pages.index');
    Route::get('/admin/pages/create', [\App\Application\Http\Controllers\Admin\PagesController::class, 'create'])->name('admin.pages.create');
    Route::post('/admin/pages', [\App\Application\Http\Controllers\Admin\PagesController::class, 'store'])->name('admin.pages.store');
    Route::get('/admin/pages/{slug}/edit', [\App\Application\Http\Controllers\Admin\PagesController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/admin/pages/{slug}', [\App\Application\Http\Controllers\Admin\PagesController::class, 'update'])->name('admin.pages.update');

    Route::get('/admin/elements', [\App\Application\Http\Controllers\Admin\ElementsController::class, 'index'])->name('admin.elements');
    Route::post('/admin/markdown-to-html', [\App\Application\Http\Controllers\Admin\MarkdownController::class, 'index'])->name('admin.markdown-to-html');
});
