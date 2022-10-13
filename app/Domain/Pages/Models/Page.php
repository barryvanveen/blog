<?php

declare(strict_types=1);

namespace App\Domain\Pages\Models;

use DateTimeInterface;

class Page
{
    public function __construct(private string $content, private string $description, private DateTimeInterface $lastUpdated, private string $slug, private string $title)
    {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function lastUpdated(): DateTimeInterface
    {
        return $this->lastUpdated;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'description' => $this->description,
            'lastUpdated' => $this->lastUpdated,
            'slug' => $this->slug,
            'title' => $this->title,
        ];
    }
}
