<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Core\CollectionInterface;

interface QueryBuilderInterface
{
    public function table(string $table): QueryBuilderInterface;

    public function get(array $columns = ['*']): CollectionInterface;

    public function first(): object;

    public function insert(array $values): bool;

    public function orderBy(string $column, string $direction = 'asc'): QueryBuilderInterface;

    public function update(array $values): int;

    public function where(string $column, string $operator, string $value, string $boolean = 'and'):
    QueryBuilderInterface;
}
