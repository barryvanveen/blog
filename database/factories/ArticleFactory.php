<?php

declare(strict_types=1);

use App\Application\Core\UniqueIdGenerator;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(ArticleEloquentModel::class, function (Faker $faker) {
    $uniqueIdGenerator = new UniqueIdGenerator();

    $title = $faker->realText(200);

    return [
        'content' => $faker->paragraph,
        'description' => $faker->paragraph,
        'published_at' => $faker->dateTimeBetween('-1 year', '-1 hour'),
        'slug' => Str::slug($title),
        'status' => ArticleStatus::published(),
        'title' => $title,
        'uuid' => $uniqueIdGenerator->generate(),
    ];
});

$factory->state(ArticleEloquentModel::class, 'published', [
    'status' => ArticleStatus::published(),
]);

$factory->state(ArticleEloquentModel::class, 'unpublished', [
    'status' => ArticleStatus::published(),
]);

$factory->state(ArticleEloquentModel::class, 'published_in_past', function (Faker $faker) {
    return [
        'published_at' => $faker->dateTimeBetween('-1 year', '-1 hour'),
    ];
});

$factory->state(ArticleEloquentModel::class, 'published_in_future', function (Faker $faker) {
    return [
        'published_at' => $faker->dateTimeBetween('+1 hour', '+1 year'),
    ];
});
