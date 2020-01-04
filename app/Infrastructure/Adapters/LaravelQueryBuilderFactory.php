<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\QueryBuilderFactoryInterface;
use App\Application\Interfaces\QueryBuilderInterface;
use Illuminate\Database\DatabaseManager;

class LaravelQueryBuilderFactory implements QueryBuilderFactoryInterface
{
    /** @var DatabaseManager */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function table(string $table): QueryBuilderInterface
    {
        return new LaravelQueryBuilder($this->databaseManager->table($table));
    }
}
