<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Comments\Comment;

interface MailerInterface
{
    public function sendLockoutTriggeredEmail(
        string $email,
        string $ip
    ): void;

    public function sendNewCommentEmail(
        Comment $comment
    ): void;
}
