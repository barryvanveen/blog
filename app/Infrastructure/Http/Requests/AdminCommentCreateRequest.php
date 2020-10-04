<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Comments\Requests\AdminCommentCreateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminCommentCreateRequest extends BaseRequest implements AdminCommentCreateRequestInterface
{
    public function rules(): array
    {
        return [
            'article_uuid' => 'required|string',
            'content' => 'required|string',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
            'email' => 'required|email',
            'ip' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|string',
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

    public function createdAt(): string
    {
        return $this->getInputParameterAsString('created_at');
    }

    public function email(): string
    {
        return $this->getInputParameterAsString('email');
    }

    public function ip(): string
    {
        return $this->getInputParameterAsString('ip');
    }

    public function name(): string
    {
        return $this->getInputParameterAsString('name');
    }

    public function status(): int
    {
        return $this->getInputParameterAsInteger('status');
    }
}
