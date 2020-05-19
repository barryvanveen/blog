<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Core\CollectionInterface;
use Illuminate\Database\Eloquent\Builder;

class LaravelQueryBuilder implements QueryBuilderInterface
{
    /** @var Builder */
    private $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function get(array $columns = ['*']): CollectionInterface
    {
        /** @psalm-suppress PossiblyInvalidMethodCall */
        return new LaravelCollection($this->builder->get($columns)->toArray());
    }

    /**
     * @return array
     * @throws RecordNotFoundException
     */
    public function first(): array
    {
        $result = $this->builder->first();

        if ($result === null) {
            throw RecordNotFoundException::emptyResultSet();
        }

        return $result->toArray();
    }

    public function insert(array $values): void
    {
        $this->builder->create($values);
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
        string $value = '',
        string $boolean = 'and'
    ): QueryBuilderInterface {
        $this->builder->where($column, $operator, $value, $boolean);

        return $this;
    }
}
