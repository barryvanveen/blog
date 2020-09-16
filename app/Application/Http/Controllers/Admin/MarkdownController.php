<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Domain\Articles\Requests\AdminMarkdownToHtmlRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MarkdownController
{
    /** @var MarkdownConverterInterface */
    private $markdownConverter;

    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    public function __construct(
        MarkdownConverterInterface $markdownConverter,
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->markdownConverter = $markdownConverter;
        $this->responseBuilder = $responseBuilder;
    }

    public function index(AdminMarkdownToHtmlRequestInterface $request): ResponseInterface
    {
        $markdown = $request->markdown();

        $html = $this->markdownConverter->convertToHtml($markdown);

        return $this->responseBuilder->json([
            'html' => $html,
        ]);
    }
}
