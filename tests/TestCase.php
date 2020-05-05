<?php

declare(strict_types=1);

namespace Tests;

use App\Domain\Pages\Models\Page;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nyholm\Psr7\Factory\Psr17Factory;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ProphecyTrait;

    protected function getStreamFactory(): StreamFactoryInterface
    {
        return new Psr17Factory();
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return new Psr17Factory();
    }

    protected function getPage(array $attributes = []): Page
    {
        return new Page(
            $attributes['content'] ?? 'mycontent',
            $attributes['description'] ?? 'mydescription',
            $attributes['lastUpdated'] ?? new DateTimeImmutable('now'),
            $attributes['slug'] ?? 'myslug',
            $attributes['title'] ?? 'mytitle'
        );
    }
}
