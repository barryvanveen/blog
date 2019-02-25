<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\Core\CommandBusInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use Carbon\Carbon;
use Redirect;

final class ArticlesController extends Controller
{
    public function index()
    {
        $viewModel = new ArticlesIndexViewModel(
            new ArticleRepository()
        );

        return view('pages.articles.index', $viewModel);
    }

    public function store(CommandBusInterface $commandBus)
    {
        $command = new CreateArticle(
            1,
            'baz',
            'bar',
            Carbon::now(),
            ArticleStatus::PUBLISHED(),
            'Foo title'
        );

        $commandBus->dispatch($command);

        return Redirect::route('articles.index');
    }
}
