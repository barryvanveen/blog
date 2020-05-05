<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Application\Pages\ModelMapperInterface;
use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;
use App\Infrastructure\Adapters\LaravelCollection;
use Carbon\Carbon;
use DateTime;
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

    public function mapToDomainModel(object $model): Page
    {
        $date = $model->updated_at;

        if ($date instanceof Carbon) {
            $date = $date->format(DateTime::ATOM);
        }

        return new Page(
            $model->content,
            $model->description,
            new DateTimeImmutable($date),
            $model->slug,
            $model->title
        );
    }

    public function mapToDatabaseArray(Page $page): array
    {
        $record = $page->toArray();
        unset($record['lastUpdated']);

        return $record;
    }
}
