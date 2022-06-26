<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\ArticleFactory;
use Database\Factories\PageFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        UserFactory::new()->create([
            'email' => 'admin@example.com',
            'name' => 'Barry',
        ]);

        ArticleFactory::new()->count(10)->create();

        $pages = [
            'Home',
            'About',
            'Books',
            'Music',
        ];

        foreach ($pages as $title) {
            PageFactory::new()->create([
                'title' => $title,
                'slug' => Str::slug($title),
            ]);
        }
    }
}
