<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Infrastructure\Eloquent\CommentEloquentModel
 *
 * @property string $article_uuid
 * @property string $content
 * @property string $email
 * @property string $ip
 * @property string $name
 * @property int $status
 * @property string $uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin \Eloquent
 */
class CommentEloquentModel extends Model
{
    protected $guarded = [];

    protected $table = 'comments';

    protected $keyType = 'string';

    protected $primaryKey = 'uuid';

    public $incrementing = false;
}
