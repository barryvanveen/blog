<?php

declare(strict_types=1);

namespace App\Application\Pages\Commands;

use App\Application\Core\CommandInterface;

class CreatePage implements CommandInterface
{
    public function __construct(public string $content, public string $description, public string $slug, public string $title)
    {
    }
}
