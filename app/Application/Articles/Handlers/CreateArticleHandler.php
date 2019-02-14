<?php

declare(strict_types=1);

namespace App\Application\Articles\Handlers;

use App\Application\Articles\Commands\CreateArticle;
use App\Application\Core\BaseCommandHandler;
use App\Domain\Articles\ArticleRepositoryInterface;
use App\Domain\Articles\Models\Article;

final class CreateArticleHandler extends BaseCommandHandler
{
    private $repository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->repository = $articleRepository;
    }

    /**
     * @param CreateArticle $command
     */
    public function handleCreateArticle(CreateArticle $command)
    {
        $article = Article::create(
            $command->authorId,
            $command->content,
            $command->description,
            $command->publishedAt,
            $command->status,
            $command->title
        );

        $this->repository->save($article);
    }
}
