<?php

declare(strict_types=1);

namespace Tests\Unit\Application\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\View\CsrfTokenPresenter;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\View\CsrfTokenPresenter
 */
class CsrfTokenPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsCsrfToken(): void
    {
        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->token()->willReturn('mytoken')->shouldBeCalled();

        $presenter = new CsrfTokenPresenter(
            $session->reveal()
        );

        $values = $presenter->present();

        $this->assertEquals([
            'token' => 'mytoken',
        ], $values);
    }
}
