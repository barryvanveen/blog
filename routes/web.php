<?php

declare(strict_types=1);

use App\Application\Http\Controllers\AboutController;
use App\Application\Http\Controllers\Admin\ArticlesController as AdminArticlesController;
use App\Application\Http\Controllers\Admin\ClearCacheController;
use App\Application\Http\Controllers\Admin\CommentsController as AdminCommentsController;
use App\Application\Http\Controllers\Admin\DashboardController;
use App\Application\Http\Controllers\Admin\MarkdownController;
use App\Application\Http\Controllers\Admin\PagesController;
use App\Application\Http\Controllers\ArticlesController;
use App\Application\Http\Controllers\ArticlesRssController;
use App\Application\Http\Controllers\BooksController;
use App\Application\Http\Controllers\CommentsController;
use App\Application\Http\Controllers\CsrfController;
use App\Application\Http\Controllers\HomeController;
use App\Application\Http\Controllers\LoginController;
use App\Application\Http\Controllers\MusicController;
use App\Application\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cache'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/about/books', [BooksController::class, 'index'])->name('books');
    Route::get('/articles', [ArticlesController::class, 'index'])->name('articles.index');
    Route::get('/articles/rss', [ArticlesRssController::class, 'index'])->name('articles.rss');
    Route::get('/articles/{uuid}-{slug}', [ArticlesController::class, 'show'])->name('articles.show');

    Route::post('/articles/comment', [CommentsController::class, 'store'])->name('comments.store')
        ->middleware(['throttle:comments']);

    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
});

Route::get('/about/music', [MusicController::class, 'index'])->name('music')
    ->middleware('cache:86400');

Route::get('/csrf-token', [CsrfController::class, 'csrf'])->name('csrf');

Route::get('/login', [LoginController::class, 'form'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')
    ->middleware(['throttle:login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/articles', [AdminArticlesController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/articles/create', [AdminArticlesController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/articles', [AdminArticlesController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/articles/{uuid}/edit', [AdminArticlesController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/admin/articles/{uuid}', [AdminArticlesController::class, 'update'])->name('admin.articles.update');

    Route::get('/admin/comments', [AdminCommentsController::class, 'index'])->name('admin.comments.index');
    Route::get('/admin/comments/create', [AdminCommentsController::class, 'create'])->name('admin.comments.create');
    Route::post('/admin/comments', [AdminCommentsController::class, 'store'])->name('admin.comments.store');
    Route::get('/admin/comments/{uuid}/edit', [AdminCommentsController::class, 'edit'])->name('admin.comments.edit');
    Route::put('/admin/comments/{uuid}', [AdminCommentsController::class, 'update'])->name('admin.comments.update');

    Route::get('/admin/pages', [PagesController::class, 'index'])->name('admin.pages.index');
    Route::get('/admin/pages/create', [PagesController::class, 'create'])->name('admin.pages.create');
    Route::post('/admin/pages', [PagesController::class, 'store'])->name('admin.pages.store');
    Route::get('/admin/pages/{slug}/edit', [PagesController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/admin/pages/{slug}', [PagesController::class, 'update'])->name('admin.pages.update');

    Route::post('/admin/markdown-to-html', [MarkdownController::class, 'index'])->name('admin.markdown-to-html');
    Route::post('/admin/clear-cache', [ClearCacheController::class, 'index'])->name('admin.clear-cache');
});
