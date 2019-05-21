<?php

declare(strict_types=1);

use App\Application\Core\UniqueIdGenerator;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(UserEloquentModel::class, function (Faker $faker) {
    $uniqueIdGenerator = new UniqueIdGenerator();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'uuid' => $uniqueIdGenerator->generate(),
    ];
});
