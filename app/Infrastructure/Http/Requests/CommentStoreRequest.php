<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Comments\Requests\CommentStoreRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class CommentStoreRequest extends BaseRequest implements CommentStoreRequestInterface
{
    public function rules(): array
    {
        return [
            'article_uuid' => 'required|string',
            'content' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'youshouldnotfillthisfield' => 'size:0',
        ];
    }

    public function articleUuid(): string
    {
        return $this->getInputParameterAsString('article_uuid');
    }

    public function content(): string
    {
        return $this->getInputParameterAsString('content');
    }

    public function email(): string
    {
        return $this->getInputParameterAsString('email');
    }

    public function ip(): string
    {
        return (string) parent::ip();
    }

    public function name(): string
    {
        return $this->getInputParameterAsString('name');
    }
}
