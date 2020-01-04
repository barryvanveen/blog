<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\FilesystemInterface;
use App\Application\Interfaces\ImageServerInterface;
use App\Application\Interfaces\PathBuilderInterface;
use League\Glide\Responses\PsrResponseFactory;
use League\Glide\ServerFactory;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class GlideImageServer implements ImageServerInterface
{
    /** @var \League\Glide\Server */
    private $glideServer;

    /** @psalm-suppress MissingClosureParamType */
    public function __construct(
        PathBuilderInterface $pathBuilder,
        FilesystemInterface $filesystem
    ) {
        $this->glideServer = ServerFactory::create([
           'source' => $filesystem->getDriver(),
           'cache' => $pathBuilder->storagePath(''),
           'cache_path_prefix' => 'image-cache',
            'response' => new PsrResponseFactory(new Response(), function ($stream) {
                return new Stream($stream);
            }),
        ]);
    }

    public function outputImage(string $filename, array $options): ResponseInterface
    {
        return $this->glideServer->getImageResponse($filename, $options);
    }
}
