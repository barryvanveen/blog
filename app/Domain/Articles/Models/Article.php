<?php

declare(strict_types=1);

namespace App\Domain\Articles\Models;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Authors\Models\Author;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Domain\Articles\Models\Article
 *
 * @property int $id
 * @property int $author_id
 * @property string $content
 * @property string $description
 * @property string $published_at
 * @property string $slug
 * @property int $status
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Domain\Authors\Models\Author $author
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Articles\Models\Article newQuery()
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
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static function create(
        int $authorId,
        string $content,
        string $description,
        Carbon $publishedAt,
        ArticleStatus $status,
        string $title
    ) {
        return new static([
            'author_id' => $authorId,
            'content' => $content,
            'description' => $description,
            'published_at' => $publishedAt,
            'status' => $status,
            'title' => $title,
        ]);
    }
}
