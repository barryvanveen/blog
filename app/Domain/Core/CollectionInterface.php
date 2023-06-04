<?php

declare(strict_types=1);

namespace App\Domain\Core;

use Countable;
use Traversable;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @template-extends Traversable<TKey, TValue>
 */
interface CollectionInterface extends Countable, Traversable
{
    public static function make(array $items = []): self;

    public function all(): array;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function toArray(): array;

    public function map(callable $callback): array;
}
