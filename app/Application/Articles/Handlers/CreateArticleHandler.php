<?php

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
        $article = new Article();
        $article->author_id = $command->input['author_id'];
        $article->content = $command->input['content'];
        $article->description = $command->input['description'];
        $article->published_at = $command->input['published_at'];
        $article->status = $command->input['status'];
        $article->title = $command->input['title'];

        $this->repository->save($article);
    }
}
