<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Infrastructure\Eloquent\AuthorEloquentModel
 *
 * @property string $content
 * @property string $description
 * @property string $name
 * @property string $uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ArticleEloquentModel[] $articles
 * @method static Builder|AuthorEloquentModel newModelQuery()
 * @method static Builder|AuthorEloquentModel newQuery()
 * @method static Builder|AuthorEloquentModel query()
 * @method static Builder|AuthorEloquentModel whereContent($value)
 * @method static Builder|AuthorEloquentModel whereCreatedAt($value)
 * @method static Builder|AuthorEloquentModel whereDescription($value)
 * @method static Builder|AuthorEloquentModel whereName($value)
 * @method static Builder|AuthorEloquentModel whereUpdatedAt($value)
 * @method static Builder|AuthorEloquentModel whereUuid($value)
 * @mixin \Eloquent
 */
class AuthorEloquentModel extends Model
{
    protected $guarded = [];

    protected $table = 'authors';

    public function articles(): HasMany
    {
        return $this->hasMany(ArticleEloquentModel::class);
    }
}
