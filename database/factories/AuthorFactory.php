<?php

use App\Domain\Authors\Models\Author;
use App\Infrastructure\Faker\LoremHtmlProvider;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Author::class, function (Faker $faker) {
    $faker->addProvider(new LoremHtmlProvider($faker));

    return [
        'content' => $faker->htmlParagraphs,
        'description' => $faker->htmlParagraph,
        'name' => $faker->name,
    ];
});
