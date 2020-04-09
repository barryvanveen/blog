<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Core\CollectionInterface;
use Illuminate\Support\Collection;
use IteratorIterator;

class LaravelCollection extends IteratorIterator implements CollectionInterface
{
    /** @var Collection */
    protected $collection;

    public function __construct(array $items = [])
    {
        $this->collection = new Collection($items);

        parent::__construct($this->collection);
    }

    public static function make(array $items = []): CollectionInterface
    {
        return new static($items);
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
