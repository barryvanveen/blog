<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Infrastructure\Eloquent\PageEloquentModel
 *
 * @property string $content
 * @property string $description
 * @property string $slug
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin \Eloquent
 */
class PageEloquentModel extends Model
{
    protected $guarded = [];

    protected $table = 'pages';
}
