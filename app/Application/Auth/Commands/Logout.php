<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Domain\Core\CommandInterface;

class Logout implements CommandInterface
{
    public function __construct()
    {
    }
}
