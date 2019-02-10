<?php

namespace App\Http\Controllers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\CommandBusInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use Redirect;

final class BlogController extends Controller
{
    public function index()
    {
        // todo: is there a way to inject this while still allowing us to pass data to it?
        $viewModel = new ArticlesIndexViewModel(
            new ArticleRepository()
        );

        return view('pages.blog.index', $viewModel);
    }

    // todo: rename Blog to Article(s)
    // todo: more files to /Core
    // todo: try to move more code to /app/Infrastructure

    public function store(CommandBusInterface $commandBus)
    {
        $command = new CreateArticle([
            'author_id' => 1,
            'content' => 'baz',
            'description' => 'bar',
            'published_at' => now(),
            'status' => ArticleStatus::PUBLISHED(),
            'title' => 'Foo title',
        ]);

        $commandBus->dispatch($command);
        // todo: catch exceptions

        return Redirect::route('blog.index');
    }
}
