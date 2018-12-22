<?php

use App\Blog\Enums\ArticleStatus;
use App\Blog\Models\Article;
use App\Blog\Models\Author;
use App\Faker\Providers\LoremHtml;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Article::class, function (Faker $faker) {
    $faker->addProvider(new LoremHtml($faker));

    $title = $faker->realText(200);

    return [
        'author_id' => function () {
            return factory(Author::class)->make()->id;
        },
        'content' => $faker->htmlArticle,
        'description' => $faker->htmlParagraph,
        'published_at' => $faker->dateTimeBetween('-1 year', '-1 hour'),
        'slug' => Str::slug($title),
        'status' => ArticleStatus::PUBLISHED(),
        'title' => $title,
    ];
});

$factory->state(Article::class, 'published', [
    'status' => ArticleStatus::PUBLISHED(),
]);

$factory->state(Article::class, 'unpublished', [
    'status' => ArticleStatus::PUBLISHED(),
]);

$factory->state(Article::class, 'published_in_past', function (Faker $faker) {
    return [
        'published_at' => $faker->dateTimeBetween('-1 year', '-1 hour'),
    ];
});

$factory->state(Article::class, 'published_in_future', function (Faker $faker) {
    return [
        'published_at' => $faker->dateTimeBetween('+1 hour', '+1 year'),
    ];
});
