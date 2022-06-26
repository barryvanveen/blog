<?php

declare(strict_types=1);

namespace App\Domain\Music;

class Album
{
    public function __construct(
        private string $artist,
        private string $name,
        private ?string $image,
    ) {
    }

    public function artist(): string
    {
        return $this->artist;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function image(): ?string
    {
        return $this->image;
    }
}
