<?php

namespace App\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Authors\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Domain\Articles\Models\Article.
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article newestToOldest()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article whereUpdatedAt($value)
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

    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
