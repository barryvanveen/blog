<?php

declare(strict_types=1);

namespace App\Application\Core;

class UniqueIdGenerator implements UniqueIdGeneratorInterface
{
    public function generate(): string
    {
        $bytes = openssl_random_pseudo_bytes(4);
        return bin2hex($bytes);
    }
}
