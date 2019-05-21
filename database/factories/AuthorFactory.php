<?php

declare(strict_types=1);

use App\Application\Core\UniqueIdGenerator;
use App\Infrastructure\Eloquent\AuthorEloquentModel;
use App\Infrastructure\Faker\LoremHtmlProvider;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(AuthorEloquentModel::class, function (Faker $faker) {
    $uniqueIdGenerator = new UniqueIdGenerator();
    $faker->addProvider(new LoremHtmlProvider($faker));

    return [
        'content' => $faker->htmlParagraphs,
        'description' => $faker->htmlParagraph,
        'name' => $faker->name,
        'uuid' => $uniqueIdGenerator->generate(),
    ];
});
