<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use App\Application\Pages\ModelMapperInterface;
use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;
use App\Infrastructure\Adapters\LaravelCollection;

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
        return new Page(
            $model->content,
            $model->description,
            $model->slug,
            $model->title
        );
    }
}
