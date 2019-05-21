<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Core\CollectionInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;

class LaravelQueryBuilder implements QueryBuilderInterface
{
    /** @var DatabaseManager */
    private $databaseManager;

    /** @var Builder */
    private $builder;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function table(string $table): QueryBuilderInterface
    {
        $this->builder = $this->databaseManager->table($table);

        return $this;
    }

    public function get(array $columns = ['*']): CollectionInterface
    {
        return new LaravelCollection($this->builder->get($columns)->toArray());
    }

    public function insert(array $values): bool
    {
        return $this->builder->insert($values);
    }

    public function orderBy(string $column, string $direction = 'asc'): QueryBuilderInterface
    {
        $this->builder->orderBy($column, $direction);

        return $this;
    }

    public function update(array $values): int
    {
        return $this->builder->update($values);
    }

    public function where(
        string $column,
        string $operator = null,
        $value = null,
        string $boolean = 'and'
    ): QueryBuilderInterface {
        $this->builder->where($column, $operator, $value, $boolean);

        return $this;
    }
}
