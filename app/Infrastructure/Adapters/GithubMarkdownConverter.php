<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\CacheInterface;
use App\Application\Interfaces\ConfigurationInterface;
use App\Application\Interfaces\MarkdownConverterInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

class GithubMarkdownConverter implements MarkdownConverterInterface
{
    private const CACHE_TTL_IN_SECONDS = 86400; // 24 hours

    private UriFactoryInterface $uriFactory;
    private StreamFactoryInterface $streamFactory;
    private RequestFactoryInterface $requestFactory;
    private ClientInterface $client;
    private CacheInterface $cache;
    private LoggerInterface $logger;
    private ConfigurationInterface $configuration;

    public function __construct(
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory,
        RequestFactoryInterface $requestFactory,
        ClientInterface $client,
        CacheInterface $cache,
        LoggerInterface $logger,
        ConfigurationInterface $configuration
    ) {
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
        $this->client = $client;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    public function convertToHtml(string $markdown): string
    {
        $cacheKey = $this->getCacheKey($markdown);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $request = $this->buildRequest($markdown);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            $this->logger->alert('Failed to convert markdown to html', [
                'exception' => $exception,
            ]);

            return 'Error';
        }

        $html = (string) $response->getBody();

        $this->cache->put($cacheKey, $html, self::CACHE_TTL_IN_SECONDS);

        return $html;
    }

    private function getCacheKey(string $markdown): string
    {
        return sha1($markdown);
    }

    private function buildRequest(string $markdown): RequestInterface
    {
        $uri = $this->uriFactory->createUri('https://api.github.com/markdown');

        $body = json_encode([
            'text' => $markdown,
            'mode' => 'markdown',
        ]);

        $stream = $this->streamFactory->createStream($body);

        $accessToken = $this->configuration->string('github.access_token');

        return $this->requestFactory->createRequest('POST', $uri)
            ->withHeader('Accept', 'application/vnd.github.v3+json')
            ->withHeader('Authorization', 'token '.$accessToken)
            ->withBody($stream);
    }
}
