<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getStreamFactory(): StreamFactoryInterface
    {
        return new Psr17Factory();
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return new Psr17Factory();
    }
}
