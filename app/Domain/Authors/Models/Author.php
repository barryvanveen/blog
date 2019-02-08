<?php

namespace App\Domain\Authors\Models;

use App\Domain\Blog\Models\Article;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Authors\Models\Author.
 *
 * @property int                                                                        $id
 * @property string                                                                     $content
 * @property string                                                                     $description
 * @property string                                                                     $name
 * @property \Illuminate\Support\Carbon|null                                            $created_at
 * @property \Illuminate\Support\Carbon|null                                            $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Domain\Blog\Models\Article[] $articles
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Authors\Models\Author whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Author extends Model
{
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
