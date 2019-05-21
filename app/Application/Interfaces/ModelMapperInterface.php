<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Articles\Models\Article;
use App\Domain\Core\CollectionInterface;
use stdClass;

interface ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface;

    public function mapToDomainModel(stdClass $model): Article;
}
