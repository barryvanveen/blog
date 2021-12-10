<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\Core\UniqueIdGeneratorInterface;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

class CreateUser extends Command
{
    /** @var string */
    protected $signature = 'create-user {name} {email} {password}';

    /** @var string */
    protected $description = 'Create a user';

    private UniqueIdGeneratorInterface $uniqueIdGenerator;
    private Hasher $hasher;

    public function __construct(
        UniqueIdGeneratorInterface $uniqueIdGenerator,
        Hasher $hasher,
    ) {
        parent::__construct();

        $this->uniqueIdGenerator = $uniqueIdGenerator;
        $this->hasher = $hasher;
    }

    public function handle(): void
    {
        $user = new UserEloquentModel();
        $user->uuid = $this->uniqueIdGenerator->generate();
        $user->name = $this->getStringArgument('name');
        $user->email = $this->getStringArgument('email');
        $user->password = $this->hasher->make($this->getStringArgument('password'));
        $user->save();
    }

    private function getStringArgument(string $name): string
    {
        return (string) $this->input->getArgument($name);
    }
}
