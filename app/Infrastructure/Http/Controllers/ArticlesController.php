<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Articles\ArticleRepository;
use App\Application\Articles\Commands\CreateArticle;
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;
use App\Application\Core\CommandBusInterface;
use App\Domain\Articles\Enums\ArticleStatus;
use DateTimeImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ArticlesController extends Controller
{
    public function index(ArticleRepository $articleRepository): View
    {
        $viewModel = new ArticlesIndexViewModel($articleRepository);

        return $this->viewFactory->make('pages.articles.index', $viewModel->toArray());
    }

    public function store(CommandBusInterface $commandBus): RedirectResponse
    {
        $command = new CreateArticle(
            '1',
            'baz',
            'bar',
            new DateTimeImmutable(),
            ArticleStatus::published(),
            'Foo title'
        );

        $commandBus->dispatch($command);

        return $this->redirector->route('articles.index');
    }
}
