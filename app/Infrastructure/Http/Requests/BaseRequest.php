<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Infrastructure\Exceptions\InvalidInputParameterException;
use App\Infrastructure\Exceptions\InvalidRouteParameterException;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getRouteParameterAsString(string $parameter): string
    {
        $value = $this->route($parameter);

        if (is_string($value) === false) {
            throw InvalidRouteParameterException::becauseParameterShouldBeAString($parameter);
        }

        return $value;
    }

    protected function getInputParameterAsString(string $parameter): string
    {
        $value = $this->input($parameter);

        if (is_string($value) === false) {
            throw InvalidInputParameterException::becauseParameterShouldBeAString($parameter);
        }

        return $value;
    }

    protected function getInputParameterAsNullableString(string $parameter): string
    {
        $value = $this->input($parameter);

        if ($value === null) {
            return '';
        }

        if (is_string($value) === false) {
            throw InvalidInputParameterException::becauseParameterShouldBeAString($parameter);
        }

        return $value;
    }

    protected function getInputParameterAsInteger(string $parameter): int
    {
        $value = $this->input($parameter);

        if ($value === null || $value === false) {
            throw InvalidInputParameterException::becauseParameterShouldBeAnInteger($parameter);
        }

        return (int) $value;
    }

    protected function getFilledParameter(string $parameter): bool
    {
        return $this->filled($parameter);
    }
}
