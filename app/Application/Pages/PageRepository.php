<?php

declare(strict_types=1);

namespace App\Application\Pages;

use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderFactoryInterface;
use App\Application\Pages\Events\PageWasUpdated;
use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;

final class PageRepository implements PageRepositoryInterface
{
    /** @var QueryBuilderFactoryInterface */
    private $builderFactory;

    /** @var ModelMapperInterface */
    private $modelMapper;

    /** @var EventBusInterface */
    private $eventBus;

    public function __construct(
        QueryBuilderFactoryInterface $builderFactory,
        ModelMapperInterface $modelMapper,
        EventBusInterface $eventBus
    ) {
        $this->builderFactory = $builderFactory;
        $this->modelMapper = $modelMapper;
        $this->eventBus = $eventBus;
    }

    public function allOrdered(): CollectionInterface
    {
        $pages = $this->builderFactory
            ->table('pages')
            ->orderBy('slug', 'asc')
            ->get();

        return $this->modelMapper->mapToDomainModels($pages);
    }

    public function insert(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->builderFactory
            ->table('pages')
            ->insert($record);
    }

    public function update(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->builderFactory
            ->table('pages')
            ->where('slug', '=', $page->slug())
            ->update($record);

        $this->eventBus->dispatch(new PageWasUpdated($page->slug()));
    }

    public function getBySlug(string $slug): Page
    {
        $page = $this->builderFactory
            ->table('pages')
            ->where('slug', '=', $slug)
            ->first();

        return $this->modelMapper->mapToDomainModel($page);
    }
}
