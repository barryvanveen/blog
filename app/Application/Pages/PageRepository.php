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
    private const SLUG_BOOKS = 'books';
    private const SLUG_HOME = 'home';
    private const SLUG_MUSIC = 'music';

    public function __construct(
        private QueryBuilderInterface $queryBuilder,
        private ModelMapperInterface $modelMapper,
        private EventBusInterface $eventBus,
    ) {
    }

    public function allOrdered(): CollectionInterface
    {
        $pages = $this->queryBuilder
            ->new()
            ->orderBy('slug', 'asc')
            ->get();

        return $this->modelMapper->mapToDomainModels($pages);
    }

    public function insert(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->queryBuilder
            ->new()
            ->insert($record);
    }

    public function update(Page $page): void
    {
        $record = $this->modelMapper->mapToDatabaseArray($page);

        $this->queryBuilder
            ->new()
            ->where('slug', '=', $page->slug())
            ->update($record);

        $this->eventBus->dispatch(new PageWasUpdated($page->slug()));
    }

    public function getBySlug(string $slug): Page
    {
        $page = $this->queryBuilder
            ->new()
            ->where('slug', '=', $slug)
            ->first();

        return $this->modelMapper->mapToDomainModel($page);
    }

    public function about(): Page
    {
        return $this->getBySlug(self::SLUG_ABOUT);
    }

    public function books(): Page
    {
        return $this->getBySlug(self::SLUG_BOOKS);
    }

    public function home(): Page
    {
        return $this->getBySlug(self::SLUG_HOME);
    }

    public function music(): Page
    {
        return $this->getBySlug(self::SLUG_MUSIC);
    }
}
