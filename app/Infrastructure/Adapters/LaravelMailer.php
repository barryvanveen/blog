<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\MailerInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Infrastructure\Mail\MarkdownMailable;
use Illuminate\Contracts\Mail\Factory;
use Illuminate\Mail\Mailer;

class LaravelMailer implements MailerInterface
{
    /** @var Mailer */
    private $mailer;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(
        Factory $factory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->mailer = $factory->mailer();
        $this->urlGenerator = $urlGenerator;
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
