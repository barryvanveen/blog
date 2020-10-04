<?php

declare(strict_types=1);

namespace App\Application\Comments;

use App\Domain\Comments\Comment;
use App\Domain\Core\CollectionInterface;

interface ModelMapperInterface
{
    public function mapToDomainModels(CollectionInterface $models): CollectionInterface;

    public function mapToDomainModel(array $model): Comment;
}
