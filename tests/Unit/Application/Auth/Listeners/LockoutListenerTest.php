<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Auth\Listeners;

use App\Application\Auth\Events\LockoutWasTriggered;
use App\Application\Auth\Listeners\LockoutListener;
use App\Application\Interfaces\MailerInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @covers \App\Application\Auth\Events\LockoutWasTriggered
 * @covers \App\Application\Auth\Listeners\LockoutListener
 */
class LockoutListenerTest extends TestCase
{
    /** @test */
    public function itLogsAndMails(): void
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning(Argument::type('string'))->shouldBeCalled();

        /** @var MailerInterface|ObjectProphecy $mailer */
        $mailer = $this->prophesize(MailerInterface::class);
        $mailer->sendLockoutTriggeredEmail(Argument::cetera())->shouldBeCalled();

        $listener = new LockoutListener(
            $logger->reveal(),
            $mailer->reveal()
        );

        $listener->handle(new LockoutWasTriggered(
            'name@domain.tld',
            '321.321.321.321'
        ));
    }
}
