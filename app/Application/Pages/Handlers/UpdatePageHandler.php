<?php

declare(strict_types=1);

namespace App\Application\Pages\Handlers;

use App\Application\Core\BaseCommandHandler;
use App\Application\Pages\Commands\UpdatePage;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;
use DateTimeImmutable;

final class UpdatePageHandler extends BaseCommandHandler
{
    /** @var PageRepositoryInterface */
    private $repository;

    public function __construct(
        PageRepositoryInterface $articleRepository
    ) {
        $this->repository = $articleRepository;
    }

    public function handleUpdatePage(UpdatePage $command): void
    {
        $page = new Page(
            $command->content,
            $command->description,
            new DateTimeImmutable(),
            $command->slug,
            $command->title
        );

        $this->repository->update($page);
    }
}
