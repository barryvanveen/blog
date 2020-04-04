<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\AdminArticleCreateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminArticleCreateRequest extends BaseRequest implements AdminArticleCreateRequestInterface
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'published_at' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function title(): string
    {
        return $this->getInputParameterAsString('title');
    }

    public function publishedAt(): string
    {
        return $this->getInputParameterAsString('published_at');
    }

    public function description(): string
    {
        return $this->getInputParameterAsString('description');
    }

    public function content(): string
    {
        return $this->getInputParameterAsString('content');
    }

    public function status(): int
    {
        return $this->getInputParameterAsInteger('status');
    }
}
