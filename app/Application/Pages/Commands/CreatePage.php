<?php

declare(strict_types=1);

namespace App\Application\Pages\Commands;

use App\Application\Core\CommandInterface;

class CreatePage implements CommandInterface
{
    /** @var string */
    public $content;

    /** @var string */
    public $description;

    /** @var string */
    public $slug;

    /** @var string */
    public $title;

    public function __construct(
        string $content,
        string $description,
        string $slug,
        string $title
    ) {
        $this->content = $content;
        $this->description = $description;
        $this->slug = $slug;
        $this->title = $title;
    }
}
