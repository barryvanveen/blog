<?php

declare(strict_types=1);

namespace App\Domain\Pages;

use App\Domain\Core\CollectionInterface;
use App\Domain\Pages\Models\Page;

interface PageRepositoryInterface
{
    public function allOrdered(): CollectionInterface;

    public function insert(Page $page): void;

    public function update(Page $page): void;

    public function getBySlug(string $slug): Page;
}
