<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Interfaces\UrlGeneratorInterface;
use App\Infrastructure\Adapters\LaravelMailer;
use App\Infrastructure\Mail\MarkdownMailable;
use Illuminate\Contracts\Mail\Factory;
use Illuminate\Support\Testing\Fakes\MailFake;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelMailer
 * @covers \App\Infrastructure\Mail\MarkdownMailable
 */
class LaravelMailerTest extends TestCase
{
    /** @test */
    public function itSendsTheLockoutTriggeredEmail(): void
    {
        $mailer = new MailFake();

        /** @var Factory|ObjectProphecy $factory */
        $factory = $this->prophesize(Factory::class);
        $factory->mailer()->willReturn($mailer);

        /** @var UrlGeneratorInterface|ObjectProphecy $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'))->willReturn('myUrl');

        $laravelMailer = new LaravelMailer(
            $factory->reveal(),
            $urlGenerator->reveal()
        );

        $laravelMailer->sendLockoutTriggeredEmail(
            'name@domain.tld',
            '234.234.234.234'
        );

        $mailer->assertSent(function (MarkdownMailable $mail) {
            return $mail->subject === 'Lockout triggered';
        });
    }

    /** @test */
    public function itSendsTheNewCommentEmail(): void
    {
        $mailer = new MailFake();

        /** @var Factory|ObjectProphecy $factory */
        $factory = $this->prophesize(Factory::class);
        $factory->mailer()->willReturn($mailer);

        /** @var UrlGeneratorInterface|ObjectProphecy $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::type('string'), Argument::type('array'))->willReturn('myUrl');

        $laravelMailer = new LaravelMailer(
            $factory->reveal(),
            $urlGenerator->reveal()
        );

        $comment = $this->getComment();

        $laravelMailer->sendNewCommentEmail(
            $comment
        );

        $mailer->assertSent(function (MarkdownMailable $mail) {
            return $mail->subject === 'New comment';
        });
    }
}
