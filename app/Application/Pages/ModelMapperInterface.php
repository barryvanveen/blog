<?php

declare(strict_types=1);

namespace App\Application\Pages;

use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;

interface ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface;

    public function mapToDomainModel(array $model): Page;

    public function mapToDatabaseArray(Page $page): array;
}
