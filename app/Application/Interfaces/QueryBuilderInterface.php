<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Core\CollectionInterface;

interface QueryBuilderInterface
{
    public function get(array $columns = ['*']): CollectionInterface;

    public function first(): array;

    public function insert(array $values): void;

    public function orderBy(string $column, string $direction = 'asc'): QueryBuilderInterface;

    public function update(array $values): int;

    public function where(string $column, string $operator, string $value, string $boolean = 'and'):
    QueryBuilderInterface;
}
