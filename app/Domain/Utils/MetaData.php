<?php

declare(strict_types=1);

namespace App\Domain\Utils;

final class MetaData
{
    public const TYPE_WEBSITE = 'website';

    public const TYPE_ARTICLE = 'article';

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $url;

    /** @var string */
    private $type;

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
        return $this->title . '  - A blog about web development - Barry van Veen';
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
