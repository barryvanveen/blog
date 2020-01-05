<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface TranslatorInterface
{
    public function get(string $key, array $replace = []): string;

    public function choice(string $key, int $number, array $replace = []): string;
}
