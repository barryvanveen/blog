<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers\Admin;

use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\MarkdownConverterInterface;
use App\Domain\Articles\Requests\AdminMarkdownToHtmlRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MarkdownController
{
    public function __construct(
        private MarkdownConverterInterface $markdownConverter,
        private ResponseBuilderInterface $responseBuilder,
    ) {
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
