<?php

declare(strict_types=1);

namespace App\Application\Articles\Handlers;

use App\Application\Articles\Commands\UpdateArticle;
use App\Application\Core\BaseCommandHandler;
use App\Application\Interfaces\SlugFactoryInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class UpdateArticleHandler extends BaseCommandHandler
{
    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var SlugFactoryInterface */
    private $slugFactory;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        SlugFactoryInterface $slugFactory
    ) {
        $this->repository = $articleRepository;
        $this->slugFactory = $slugFactory;
    }

    public function handleUpdateArticle(UpdateArticle $command): void
    {
        $article = new Article(
            $command->content,
            $command->description,
            $command->publishedAt,
            $this->slugFactory->slug($command->title),
            $command->status,
            $command->title,
            $command->uuid
        );

        $this->repository->update($article);
    }
}
