<?php

declare(strict_types=1);

namespace App\Application\Articles;

use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;

interface ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface;

    public function mapToDomainModel(object $model): Article;
}
