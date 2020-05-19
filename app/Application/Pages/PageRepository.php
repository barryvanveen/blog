<?php

declare(strict_types=1);

namespace App\Application\Pages;

use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Application\Pages\Events\PageWasUpdated;
use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;
use App\Domain\Pages\PageRepositoryInterface;

final class PageRepository implements PageRepositoryInterface
{
    private const SLUG_ABOUT = 'about';

    /** @var QueryBuilderInterface */
    private $queryBuilder;

    /** @var ModelMapperInterface */
    private $modelMapper;

    /** @var EventBusInterface */
    private $eventBus;

    public function __construct(
        QueryBuilderInterface $queryBuilder,
        ModelMapperInterface $modelMapper,
        EventBusInterface $eventBus
    ) {
        $this->queryBuilder = $queryBuilder;
        $this->modelMapper = $modelMapper;
        $this->eventBus = $eventBus;
    }

    public function allOrdered(): CollectionInterface
    {
        $pages = $this->queryBuilder
            ->orderBy('slug', 'asc')
            ->get();

        return $this->modelMapper->mapToDomainModels($pages);
    }

    public function insert(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->queryBuilder
            ->insert($record);
    }

    public function update(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->queryBuilder
            ->where('slug', '=', $page->slug())
            ->update($record);

        $this->eventBus->dispatch(new PageWasUpdated($page->slug()));
    }

    public function about(): Page
    {
        return $this->getBySlug(self::SLUG_ABOUT);
    }

    public function getBySlug(string $slug): Page
    {
        $page = $this->queryBuilder
            ->where('slug', '=', $slug)
            ->first();

        return $this->modelMapper->mapToDomainModel($page);
    }
}
