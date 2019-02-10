<?php

namespace App\Application\Articles\Commands;

use App\Domain\CommandInterface;

class CreateArticle implements CommandInterface
{
    public $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }
}
