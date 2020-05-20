<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Application\Pages\ModelMapperInterface;
use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;
use App\Infrastructure\Adapters\LaravelCollection;
use DateTimeImmutable;

final class PageMapper implements ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface
    {
        $domainModels = [];

        foreach ($models as $model) {
            $domainModels[] = $this->mapToDomainModel($model);
        }

        return new LaravelCollection($domainModels);
    }

    public function mapToDomainModel(array $model): Page
    {
        return new Page(
            $model['content'],
            $model['description'],
            new DateTimeImmutable($model['updated_at']),
            $model['slug'],
            $model['title']
        );
    }

    public function mapToDatabaseArray(Page $page): array
    {
        $record = $page->toArray();
        unset($record['lastUpdated']);

        return $record;
    }
}
