<?php

declare(strict_types=1);

use App\Domain\Articles\Models\Article;
use App\Domain\Authors\Models\Author;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /** @var Author $author */
        $author = factory(Author::class)->create();

        /* @var Article[] $articles */
        factory(Article::class, 10)->create([
            'author_id' => $author->id,
        ]);
    }
}
