<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface QueryBuilderFactoryInterface
{
    public function table(string $table): QueryBuilderInterface;
}
