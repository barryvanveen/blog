<?php

declare(strict_types=1);

namespace App\Domain\Authors\Models;

class Author
{
    /** @var string */
    private $content;

    /** @var string */
    private $description;

    /** @var string */
    private $name;

    /** @var string */
    private $uuid;

    public function __construct(
        string $content,
        string $description,
        string $name,
        string $uuid
    ) {
        $this->content = $content;
        $this->description = $description;
        $this->name = $name;
        $this->uuid = $uuid;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'description' => $this->description,
            'name' => $this->name,
            'uuid' => $this->uuid,
        ];
    }
}
