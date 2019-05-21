<?php

declare(strict_types=1);

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\AuthorEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        factory(UserEloquentModel::class)->create([
            'email' => 'admin@example.com',
            'name' => 'Barry',
        ]);

        /** @var AuthorEloquentModel $author */
        $author = factory(AuthorEloquentModel::class)->create();

        /* @var ArticleEloquentModel[] $articles */
        factory(ArticleEloquentModel::class, 10)->create([
            'author_uuid' => $author->uuid,
        ]);
    }
}
