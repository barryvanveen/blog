<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Core;

use App\Application\Core\ResponseBuilder;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Interfaces\ViewBuilderInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tests\TestCase;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\StreamFactory;

/**
 * @covers \App\Application\Core\ResponseBuilder
 */
class ResponseBuilderTest extends TestCase
{
    /** @var ObjectProphecy|ViewBuilderInterface */
    private $viewBuilder;

    /** @var ObjectProphecy|ResponseFactoryInterface */
    private $responseFactory;

    /** @var ObjectProphecy|StreamFactoryInterface */
    private $streamFactory;

    /** @var ResponseBuilder */
    private $responseBuilder;

    /** @var ObjectProphecy|UrlGeneratorInterface */
    private $urlGenerator;

    public function setUp(): void
    {
        parent::setUp();

        $this->viewBuilder = $this->prophesize(ViewBuilderInterface::class);
        $this->responseFactory = new ResponseFactory();
        $this->streamFactory = new StreamFactory();
        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);

        $this->responseBuilder = new ResponseBuilder(
            $this->viewBuilder->reveal(),
            $this->responseFactory,
            $this->streamFactory,
            $this->urlGenerator->reveal()
        );
    }

    /** @test */
    public function itReturnsOkResponse(): void
    {
        // arrange
        $this->viewBuilder->render(Argument::type('string'), Argument::type('array'))
            ->willReturn('myViewString');

        // act
        $response = $this->responseBuilder->ok('foo.view', ['id' => 123]);

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('myViewString', (string) $response->getBody());
    }

    /** @test */
    public function itReturnsRedirectResponse(): void
    {
        // arrange
        $this->urlGenerator->route(Argument::type('string'))
            ->willReturn('fooUrl');

        // act
        $response = $this->responseBuilder->redirect(302, 'articles.index');

        // assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(302, $response->getStatusCode());

        $headers = $response->getHeaders();
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('fooUrl', $headers['Location'][0]);
    }
}
