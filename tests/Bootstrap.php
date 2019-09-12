<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class Bootstrap implements BeforeFirstTestHook, AfterLastTestHook
{
    use CreatesApplication;

    public function executeBeforeFirstTest(): void
    {
        $console = $this->createApplication()->make(Kernel::class);
        $commands = [
            'config:cache',
            'event:cache',
        ];
        foreach ($commands as $command) {
            $console->call($command);
        }
    }

    public function executeAfterLastTest(): void
    {
        array_map('unlink', glob('bootstrap/cache/*.phpunit.php'));
    }
}
