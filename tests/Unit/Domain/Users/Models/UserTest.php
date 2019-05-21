<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Users\Models;

use App\Domain\Users\Models\User;
use Tests\TestCase;

/**
 * @covers \App\Domain\Users\Models\User
 */
class UserTest extends TestCase
{
    /**
     * @test
     */
    public function itConstructsANewUser(): void
    {
        $user = new User(
            'foo@bar.com',
            'user-name',
            'secret',
            null,
            '123123'
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('foo@bar.com', $user->email());
        $this->assertEquals('user-name', $user->name());
        $this->assertEquals('secret', $user->password());
        $this->assertEquals(null, $user->rememberToken());
        $this->assertEquals('123123', $user->uuid());
    }
}
