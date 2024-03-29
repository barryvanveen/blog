<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\AdminMarkdownToHtmlRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminMarkdownToHtmlRequest extends BaseRequest implements AdminMarkdownToHtmlRequestInterface
{
    public function rules(): array
    {
        return [
            'markdown' => 'nullable',
        ];
    }

    public function markdown(): string
    {
        return $this->getInputParameterAsNullableString('markdown');
    }
}
