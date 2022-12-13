<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Core\CollectionInterface;
use ArrayIterator;
use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends ArrayIterator<TKey, TValue>
 * @implements CollectionInterface<TKey, TValue>
 */
class LaravelCollection extends ArrayIterator implements CollectionInterface
{
    protected Collection $collection;

    public function __construct(
        array $items = [],
    ) {
        $this->collection = new Collection($items);

        parent::__construct($this->collection->toArray());
    }

    public static function make(array $items = []): CollectionInterface
    {
        return new self($items);
    }

    public function all(): array
    {
        return $this->collection->all();
    }

    public function count(): int
    {
        return $this->collection->count();
    }

    public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return $this->collection->isNotEmpty();
    }

    public function toArray(): array
    {
        return $this->collection->toArray();
    }

    public function map(callable $callback): array
    {
        return $this->collection->map($callback)->toArray();
    }
}
