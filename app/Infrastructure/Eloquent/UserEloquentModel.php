<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

/**
 * App\Infrastructure\Eloquent\UserEloquentModel
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $uuid
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserEloquentModel newModelQuery()
 * @method static Builder|UserEloquentModel newQuery()
 * @method static Builder|UserEloquentModel query()
 * @method static Builder|UserEloquentModel whereCreatedAt($value)
 * @method static Builder|UserEloquentModel whereEmail($value)
 * @method static Builder|UserEloquentModel whereName($value)
 * @method static Builder|UserEloquentModel wherePassword($value)
 * @method static Builder|UserEloquentModel whereRememberToken($value)
 * @method static Builder|UserEloquentModel whereUpdatedAt($value)
 * @method static Builder|UserEloquentModel whereUuid($value)
 * @mixin \Eloquent
 */
class UserEloquentModel extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = 'users';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;
}
