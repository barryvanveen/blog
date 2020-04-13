<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Interfaces\ImageServerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ImagesController
{
    /** @var ImageServerInterface */
    private $imageServer;

    /** @var array */
    private const ALLOWED_PARAMETERS = [
        'w',
        'h',
    ];

    public function __construct(
        ImageServerInterface $imageServer
    ) {
        $this->imageServer = $imageServer;
    }

    public function show(string $filename, ServerRequestInterface $request): ResponseInterface
    {
        $params = [
            'fit' => 'max',
        ];

        foreach ($request->getQueryParams() as $key => $value) {
            if (in_array($key, self::ALLOWED_PARAMETERS)) {
                $params[$key] = $value;
            }
        }

        return $this->imageServer->outputImage($filename, $params);
    }
}
