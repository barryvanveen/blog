<?php

declare(strict_types=1);

use App\Infrastructure\Eloquent\PageEloquentModel;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(PageEloquentModel::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'content' => $faker->paragraph,
        'description' => $faker->paragraph,
        'slug' => Str::slug($title),
        'title' => $title,
        'updated_at' => $faker->dateTimeBetween('-1 year'),
    ];
});
