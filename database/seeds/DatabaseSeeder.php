<?php

declare(strict_types=1);

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\PageEloquentModel;
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

        factory(ArticleEloquentModel::class, 10)->create();

        $pages = [
            'Home',
            'About',
            'Books that I have read',
        ];

        foreach ($pages as $title) {
            factory(PageEloquentModel::class)->create([
                'title' => $title,
                'slug' => Str::slug($title),
            ]);
        }
    }
}
