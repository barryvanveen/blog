<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\ConfigurationInterface;
use App\Infrastructure\Adapters\GithubMarkdownConverter;
use GuzzleHttp\Exception\BadResponseException;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Uri;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\GithubMarkdownConverter
 */
class GithubMarkdownConverterTest extends TestCase
{
    private const RESPONSE_HTML_STRING = 'htmlString';

    /** @var ObjectProphecy|ClientInterface */
    private $client;

    /** @var ObjectProphecy|CacheInterface */
    private $cache;

    /** @var ObjectProphecy|LoggerInterface */
    private $logger;

    private GithubMarkdownConverter $githubMarkdownConverter;

    public function setUp(): void
    {
        parent::setUp();

        $uri = new Uri('http://myurl.tld');
        $stream = Stream::create('mybody');
        $request = new Request('POST', $uri, [], $stream);
        $response = new Response(200, [], Stream::create(self::RESPONSE_HTML_STRING));

        /** @var ObjectProphecy|UriFactoryInterface $uriFactory */
        $uriFactory = $this->prophesize(UriFactoryInterface::class);
        $uriFactory->createUri(Argument::any())->willReturn($uri);

        /** @var ObjectProphecy|StreamFactoryInterface $streamFactory */
        $streamFactory = $this->prophesize(StreamFactoryInterface::class);
        $streamFactory->createStream(Argument::any())->willReturn($stream);

        /** @var ObjectProphecy|RequestFactoryInterface $requestFactory */
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $requestFactory->createRequest(Argument::any(), Argument::any())->willReturn($request);

        /** @var ObjectProphecy|ClientInterface $client */
        $this->client = $this->prophesize(ClientInterface::class);
        $this->client->sendRequest(Argument::any())->willReturn($response);

        /** @var ObjectProphecy|CacheInterface cache */
        $this->cache = $this->prophesize(CacheInterface::class);
        $this->cache->put(Argument::cetera())->willReturn(true);

        $this->logger = $this->prophesize(LoggerInterface::class);

        /** @var ObjectProphecy|ConfigurationInterface $configuration */
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->string(Argument::any())->willReturn('accessToken');

        $this->githubMarkdownConverter = new GithubMarkdownConverter(
            $uriFactory->reveal(),
            $streamFactory->reveal(),
            $requestFactory->reveal(),
            $this->client->reveal(),
            $this->cache->reveal(),
            $this->logger->reveal(),
            $configuration->reveal()
        );
    }

    /** @test */
    public function itReturnsCachedResponse(): void
    {
        // arrange
        $cachedString = 'MyCachedString';

        $this->cache->has(Argument::any())->willReturn(true);
        $this->cache->get(Argument::any())->willReturn($cachedString);

        // act
        $html = $this->githubMarkdownConverter->convertToHtml('markdown');

        // assert
        $this->assertEquals($cachedString, $html);
        $this->client->sendRequest(Argument::any())->shouldNotBeCalled();
    }

    /** @test */
    public function itMakesARequest(): void
    {
        // arrange
        $this->cache->has(Argument::any())->willReturn(false);

        // act
        $html = $this->githubMarkdownConverter->convertToHtml('markdown');

        // assert
        $this->assertEquals(self::RESPONSE_HTML_STRING, $html);
        $this->client->sendRequest(Argument::any())->shouldBeCalled();
        $this->cache->put(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();
    }

    /** @test */
    public function itHandlesErrorsDuringTheRequest(): void
    {
        // arrange
        $this->cache->has(Argument::any())->willReturn(false);
        $exception = $this->createException();
        $this->client->sendRequest(Argument::any())->willThrow($exception);

        // act
        $html = $this->githubMarkdownConverter->convertToHtml('markdown');

        // assert
        $this->assertEquals('Error', $html);
        $this->client->sendRequest(Argument::any())->shouldBeCalled();
        $this->cache->put(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->logger->alert(Argument::any(), Argument::any())->shouldBeCalled();
    }

    private function createException(): ClientExceptionInterface
    {
        return new BadResponseException(
            'MyErrorMessage',
            new Request(
                'POST',
                new Uri('http://myurl.tld')
            ),
            new Response()
        );
    }
}
