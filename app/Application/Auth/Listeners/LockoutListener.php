<?php

declare(strict_types=1);

namespace App\Application\Auth\Listeners;

use App\Application\Auth\Events\LockoutWasTriggered;
use App\Application\Core\BaseEventListener;
use App\Application\Interfaces\MailerInterface;
use Psr\Log\LoggerInterface;

final class LockoutListener extends BaseEventListener
{
    public function __construct(
        private LoggerInterface $logger,
        private MailerInterface $mailer,
    ) {
    }

    public function handleLockoutWasTriggered(LockoutWasTriggered $event): void
    {
        $this->logger->warning('Lockout triggered');

        $this->mailer->sendLockoutTriggeredEmail(
            $event->email(),
            $event->ip()
        );
    }
}
