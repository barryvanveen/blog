<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Application\Core\UniqueIdGenerator;
use App\Domain\Articles\Enums\ArticleStatus;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = ArticleEloquentModel::class;

    public function definition(): array
    {
        $uniqueIdGenerator = new UniqueIdGenerator();

        $title = $this->faker->sentence;

        return [
            'content' => $this->faker->paragraph,
            'description' => $this->faker->paragraph,
            'published_at' => $this->faker->dateTimeBetween('-1 year', '-1 hour'),
            'slug' => Str::slug($title),
            'status' => ArticleStatus::published(),
            'title' => $title,
            'updated_at' => $this->faker->dateTime,
            'uuid' => $uniqueIdGenerator->generate(),
        ];
    }

    public function published(): self
    {
        return $this->state([
            'status' => ArticleStatus::published(),
        ]);
    }

    public function unpublished(): self
    {
        return $this->state([
            'status' => ArticleStatus::unpublished(),
        ]);
    }

    public function publishedInPast(): self
    {
        return $this->state([
            'published_at' => $this->faker->dateTimeBetween('-1 year', '-1 hour'),
        ]);
    }

    public function publishedInFuture(): self
    {
        return $this->state([
            'published_at' => $this->faker->dateTimeBetween('+1 hour', '+1 year'),
        ]);
    }

    public function title(string $title): self
    {
        return $this->state([
            'title' => $title,
            'slug' => Str::slug($title),
        ]);
    }

    public function uuid(string $uuid): self
    {
        return $this->state([
            'uuid' => $uuid,
        ]);
    }
}
