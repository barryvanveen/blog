<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Application\Core\UniqueIdGenerator;
use App\Domain\Comments\CommentStatus;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = CommentEloquentModel::class;

    public function definition(): array
    {
        $uniqueIdGenerator = new UniqueIdGenerator();

        return [
            'article_uuid' => $uniqueIdGenerator->generate(),
            'content' => $this->faker->paragraph,
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 hour'),
            'email' => $this->faker->email,
            'ip' => $this->faker->ipv4,
            'name' => $this->faker->name,
            'status' => CommentStatus::published(),
            'uuid' => $uniqueIdGenerator->generate(),
        ];
    }

    public function published(): self
    {
        return $this->state([
            'status' => CommentStatus::published(),
        ]);
    }

    public function unpublished(): self
    {
        return $this->state([
            'status' => CommentStatus::unpublished(),
        ]);
    }

    public function forArticle(string $articleUuid): self
    {
        return $this->state([
            'article_uuid' => $articleUuid,
        ]);
    }
}
