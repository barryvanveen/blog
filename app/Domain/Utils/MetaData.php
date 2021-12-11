<?php

declare(strict_types=1);

namespace App\Domain\Utils;

final class MetaData
{
    public const TYPE_WEBSITE = 'website';

    public const TYPE_ARTICLE = 'article';

    private string $title;

    private string $description;

    private string $url;

    private string $type;

    public function __construct(
        string $title,
        string $description,
        string $url,
        string $type
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->type = $type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function type(): string
    {
        return $this->type;
    }
}
