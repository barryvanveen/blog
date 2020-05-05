<?php

declare(strict_types=1);

namespace App\Domain\Pages\Models;

use DateTimeInterface;

class Page
{
    /** @var string */
    private $content;

    /** @var string */
    private $description;

    /** @var DateTimeInterface */
    private $lastUpdated;

    /** @var string */
    private $slug;

    /** @var string */
    private $title;

    public function __construct(
        string $content,
        string $description,
        DateTimeInterface $lastUpdated,
        string $slug,
        string $title
    ) {
        $this->content = $content;
        $this->description = $description;
        $this->lastUpdated = $lastUpdated;
        $this->slug = $slug;
        $this->title = $title;
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
