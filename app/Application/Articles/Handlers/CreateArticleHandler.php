<?php

declare(strict_types=1);

namespace App\Application\Articles\Handlers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Core\BaseCommandHandler;
use App\Application\Core\UniqueIdGeneratorInterface;
use App\Application\Interfaces\ClockInterface;
use App\Application\Interfaces\SlugFactoryInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class CreateArticleHandler extends BaseCommandHandler
{
    private UniqueIdGeneratorInterface $uniqueIdGenerator;
    private ArticleRepositoryInterface $repository;
    private SlugFactoryInterface $slugFactory;
    private ClockInterface $clock;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        UniqueIdGeneratorInterface $uniqueIdGenerator,
        SlugFactoryInterface $slugFactory,
        ClockInterface $clock,
    ) {
        $this->repository = $articleRepository;
        $this->uniqueIdGenerator = $uniqueIdGenerator;
        $this->slugFactory = $slugFactory;
        $this->clock = $clock;
    }

    public function handleCreateArticle(CreateArticle $command): void
    {
        $article = new Article(
            $command->content,
            $command->description,
            $command->publishedAt,
            $this->slugFactory->slug($command->title),
            $command->status,
            $command->title,
            $this->clock->now(),
            $this->uniqueIdGenerator->generate()
        );

        $this->repository->insert($article);
    }
}
