<?php

declare(strict_types=1);

namespace App\Application\Pages\Handlers;

use App\Application\Core\BaseCommandHandler;
use App\Application\Pages\Commands\CreatePage;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use DateTimeImmutable;

final class CreatePageHandler extends BaseCommandHandler
{
    /** @var PageRepositoryInterface */
    private $repository;

    public function __construct(
        PageRepositoryInterface $articleRepository
    ) {
        $this->repository = $articleRepository;
    }

    public function handleCreatePage(CreatePage $command): void
    {
        $page = new Page(
            $command->content,
            $command->description,
            new DateTimeImmutable(),
            $command->slug,
            $command->title
        );

        $this->repository->insert($page);
    }
}
