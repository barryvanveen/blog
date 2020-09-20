<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Eloquent\PageEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    protected $model = PageEloquentModel::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;

        return [
            'content' => $this->faker->paragraph,
            'description' => $this->faker->paragraph,
            'slug' => Str::slug($title),
            'title' => $title,
            'updated_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
