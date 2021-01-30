<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Controllers;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Http\Controllers\CsrfController;
use App\Application\Interfaces\SessionInterface;
use Nyholm\Psr7\Response;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\CsrfController
 */
class CsrfControllerTest extends TestCase
{
    /** @var ObjectProphecy|ResponseBuilderInterface */
    private $responseBuilder;

    /** @var ObjectProphecy|SessionInterface */
    private $session;

    /** @var CsrfController */
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->responseBuilder = $this->prophesize(ResponseBuilderInterface::class);
        $this->session = $this->prophesize(SessionInterface::class);

        $this->controller = new CsrfController(
            $this->responseBuilder->reveal(),
            $this->session->reveal()
        );
    }

    /** @test */
    public function itReturnsCsrfToken(): void
    {
        $token = '123foobar';

        $this->session->token()
            ->willReturn($token);

        $this->responseBuilder->json([
                'token' => $token,
            ])
            ->willReturn(new Response())
            ->shouldBeCalled();

        $this->controller->csrf();
    }
}
