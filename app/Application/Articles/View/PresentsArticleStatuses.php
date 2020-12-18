<?php

declare(strict_types=1);

namespace App\Application\Articles\View;

use App\Domain\Articles\Enums\ArticleStatus;

trait PresentsArticleStatuses
{
    private function statuses(): array
    {
        return [
            [
                'value' => (string) ArticleStatus::unpublished(),
                'title' => 'Not published',
            ],
            [
                'value' => (string) ArticleStatus::published(),
                'title' => 'Published',
            ],
        ];
    }
}
