<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MailerInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Comments\Comment;
use App\Infrastructure\Mail\MarkdownMailable;
use Illuminate\Contracts\Mail\Factory;
use Illuminate\Contracts\Mail\Mailer;

class LaravelMailer implements MailerInterface
{
    private Mailer $mailer;

    public function __construct(
        Factory $factory,
        private UrlGeneratorInterface $urlGenerator
    ) {
        $this->mailer = $factory->mailer();
    }

    public function sendLockoutTriggeredEmail(string $email, string $ip): void
    {
        $this->send(
            'emails.lockout',
            'Lockout triggered',
            [
                'email' => $email,
                'ip' => $ip,
                'admin_url' => $this->urlGenerator->route('admin.dashboard'),
            ]
        );
    }

    public function sendNewCommentEmail(Comment $comment): void
    {
        $this->send(
            'emails.new_comment',
            'New comment',
            [
                'name' => $comment->name(),
                'email' => $comment->email(),
                'content' => $comment->content(),
                'admin_url' => $this->urlGenerator->route('admin.comments.edit', ['uuid' => $comment->uuid()]),
            ]
        );
    }

    private function send(
        string $template,
        string $subject,
        array $variables
    ): void {
        $this->mailer->send(
            new MarkdownMailable(
                $template,
                $subject,
                $variables
            )
        );
    }
}
