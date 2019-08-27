<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Articles\Requests\ArticleShowRequestInterface;
use App\Infrastructure\Exceptions\InvalidRouteParameterException;
use Illuminate\Foundation\Http\FormRequest;

class ArticleShowRequest extends FormRequest implements ArticleShowRequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function uuid(): string
    {
        return $this->getParameterAsString('uuid');
    }

    public function slug(): string
    {
        return $this->getParameterAsString('slug');
    }

    private function getParameterAsString(string $parameter): string
    {
        $value = $this->route($parameter);

        if (is_string($value) === false) {
            throw InvalidRouteParameterException::becauseParameterShouldBeAString($parameter);
        }

        return $value;
    }
}
