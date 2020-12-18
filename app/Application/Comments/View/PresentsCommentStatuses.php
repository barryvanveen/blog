<?php

declare(strict_types=1);

namespace App\Application\Comments\View;

use App\Domain\Comments\CommentStatus;

trait PresentsCommentStatuses
{
    private function statuses(): array
    {
        return [
            [
                'value' => (string) CommentStatus::unpublished(),
                'title' => 'Offline',
            ],
            [
                'value' => (string) CommentStatus::published(),
                'title' => 'Online',
            ],
        ];
    }
}
