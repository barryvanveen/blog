<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Pages\Requests\AdminPageEditRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPageEditRequest extends BaseRequest implements AdminPageEditRequestInterface
{
    public function rules(): array
    {
        return [];
    }

    public function slug(): string
    {
        return $this->getRouteParameterAsString('slug');
    }
}
