<?php

namespace App\Domain\Blog\Models;

use App\Domain\Authors\Models\Author;
use App\Domain\Blog\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Blog\Models\Article.
 *
 * @property int                               $id
 * @property int                               $author_id
 * @property string                            $content
 * @property string                            $description
 * @property string                            $published_at
 * @property string                            $slug
 * @property int                               $status
 * @property string                            $title
 * @property \Illuminate\Support\Carbon|null   $created_at
 * @property \Illuminate\Support\Carbon|null   $updated_at
 * @property \App\Domain\Authors\Models\Author $author
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article newestToOldest()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Blog\Models\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', ArticleStatus::PUBLISHED());
    }

    public function scopeNewestToOldest(Builder $query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
