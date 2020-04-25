<?php

declare(strict_types=1);

namespace App\Application\Articles\Handlers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Core\BaseCommandHandler;
use App\Application\Core\UniqueIdGeneratorInterface;
use App\Application\Interfaces\SlugFactoryInterface;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class CreateArticleHandler extends BaseCommandHandler
{
    /** @var UniqueIdGeneratorInterface */
    private $uniqueIdGenerator;

    /** @var ArticleRepositoryInterface */
    private $repository;

    /** @var SlugFactoryInterface */
    private $slugFactory;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        UniqueIdGeneratorInterface $uniqueIdGenerator,
        SlugFactoryInterface $slugFactory
    ) {
        $this->repository = $articleRepository;
        $this->uniqueIdGenerator = $uniqueIdGenerator;
        $this->slugFactory = $slugFactory;
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
            $this->uniqueIdGenerator->generate()
        );

        $this->repository->insert($article);
    }
}
