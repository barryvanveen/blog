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
            'content' => 'required',
            'email' => 'required|email',
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Please fill in a message for your comment',
            'email.required' => 'Please fill in a valid email address',
            'email.email' => 'This is not a valid email address',
            'name.required' => 'Please fill in your name',
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

    public function honeypot(): string
    {
        return (string) $this->input('youshouldnotfillthisfield');
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
