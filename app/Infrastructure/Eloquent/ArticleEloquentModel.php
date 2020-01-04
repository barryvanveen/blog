<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|ArticleEloquentModel newModelQuery()
 * @method static Builder|ArticleEloquentModel newQuery()
 * @method static Builder|ArticleEloquentModel query()
 * @method static Builder|ArticleEloquentModel whereContent($value)
 * @method static Builder|ArticleEloquentModel whereCreatedAt($value)
 * @method static Builder|ArticleEloquentModel whereDescription($value)
 * @method static Builder|ArticleEloquentModel wherePublishedAt($value)
 * @method static Builder|ArticleEloquentModel whereSlug($value)
 * @method static Builder|ArticleEloquentModel whereStatus($value)
 * @method static Builder|ArticleEloquentModel whereTitle($value)
 * @method static Builder|ArticleEloquentModel whereUpdatedAt($value)
 * @method static Builder|ArticleEloquentModel whereUuid($value)
 * @mixin \Eloquent
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ArticleEloquentModel extends Model
{
    protected $guarded = [];

    protected $table = 'articles';
}
