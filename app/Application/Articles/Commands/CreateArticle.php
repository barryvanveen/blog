<?php

declare(strict_types=1);

namespace App\Application\Articles\Commands;

use App\Domain\Core\CommandInterface;

class CreateArticle implements CommandInterface
{
    public $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }
}
