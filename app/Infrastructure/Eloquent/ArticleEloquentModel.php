<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Infrastructure\Eloquent\ArticleEloquentModel
 *
 * @property string $content
 * @property string $description
 * @property string $published_at
 * @property string $slug
 * @property int $status
 * @property string $title
 * @property string $uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin \Eloquent
 */
class ArticleEloquentModel extends Model
{
    protected $guarded = [];

    protected $table = 'articles';
}
