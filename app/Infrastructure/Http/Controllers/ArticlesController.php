<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\Core\CommandBusInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ArticlesController extends Controller
{
    public function index(): View
    {
        $viewModel = new ArticlesIndexViewModel(
            new ArticleRepository()
        );

        return $this->viewFactory->make('pages.articles.index', $viewModel);
    }

    public function store(CommandBusInterface $commandBus): RedirectResponse
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

        return $this->redirector->route('articles.index');
    }
}
