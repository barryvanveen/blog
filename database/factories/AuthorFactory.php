<?php

use App\Domain\Authors\Models\Author;
use App\Faker\Providers\LoremHtml;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Author::class, function (Faker $faker) {
    $faker->addProvider(new LoremHtml($faker));

    return [
        'content' => $faker->htmlParagraphs,
        'description' => $faker->htmlParagraph,
        'name' => $faker->name,
    ];
});
