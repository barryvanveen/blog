<?php

use App\Blog\Models\Article;
use App\Blog\Models\Author;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /** @var Author $author */
        $author = factory(Author::class)->create();

        /** @var Article[] $articles */
        $articles = factory(Article::class, 10)->create([
            'author_id' => $author->id,
        ]);
    }
}
