<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Exceptions\RecordNotFoundException;
use App\Application\Interfaces\QueryBuilderInterface;
use App\Domain\Core\CollectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LaravelQueryBuilder implements QueryBuilderInterface
{
    private Builder $builder;

    public function __construct(
        private Model $model,
    ) {
        $this->builder = $model->newQuery();
    }

    public function new(): QueryBuilderInterface
    {
        $this->builder = $this->model->newQuery();

        return $this;
    }

    public function get(array $columns = ['*']): CollectionInterface
    {
        /** @psalm-suppress PossiblyInvalidMethodCall */
        return new LaravelCollection($this->builder->get($columns)->toArray());
    }

    /**
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
        string $boolean = 'and',
    ): QueryBuilderInterface {
        $this->builder->where($column, $operator, $value, $boolean);

        return $this;
    }

    public function toSql(): string
    {
        return str_replace_array(
            '?',
            $this->builder->getBindings(),
            $this->builder->toSql()
        );
    }
}
