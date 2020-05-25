<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface MailerInterface
{
    public function sendLockoutTriggeredEmail(
        string $email,
        string $ip
    ): void;
}
