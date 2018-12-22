<?php

namespace App\Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Blog\Models\Article.
 *
 * @property int                             $id
 * @property int                             $author_id
 * @property string                          $content
 * @property string                          $description
 * @property string                          $published_at
 * @property string                          $slug
 * @property int                             $status
 * @property string                          $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Blog\Models\Author         $author
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Blog\Models\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    public function author()
    {
        return $this->hasOne(Author::class);
    }
}
