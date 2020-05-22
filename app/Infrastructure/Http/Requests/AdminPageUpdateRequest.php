<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use App\Domain\Pages\Requests\AdminPageUpdateRequestInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPageUpdateRequest extends AdminPageCreateRequest implements AdminPageUpdateRequestInterface
{
}
