<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Pages\Requests\AdminPageCreateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPageCreateRequest extends BaseRequest implements AdminPageCreateRequestInterface
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function title(): string
    {
        return $this->getInputParameterAsString('title');
    }

    public function slug(): string
    {
        return $this->getInputParameterAsString('slug');
    }

    public function description(): string
    {
        return $this->getInputParameterAsString('description');
    }

    public function content(): string
    {
        return $this->getInputParameterAsString('content');
    }
}
