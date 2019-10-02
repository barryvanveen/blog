<?php

declare(strict_types=1);

namespace App\Application\View;

interface PresenterInterface
{
    public function present(): array;
}
