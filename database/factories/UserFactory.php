<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Application\Core\UniqueIdGenerator;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = UserEloquentModel::class;

    public function definition(): array
    {
        $uniqueIdGenerator = new UniqueIdGenerator();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => Str::random(10),
            'uuid' => $uniqueIdGenerator->generate(),
        ];
    }
}
