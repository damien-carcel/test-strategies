<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain;

use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testItConvertsAUserToAnArrayOfRawData(): void
    {
        $userId = UserId::create();
        $user = User::create(
            id: $userId,
            email: Email::create('gandalf.thegrey@theshire.com'),
            password: Password::create('Y0uSh@llN0tP@ss'),
        );

        $userAsAnArray = $user->toArray();

        self::assertSame((string) $userId, $userAsAnArray['id']);
        self::assertSame('gandalf.thegrey@theshire.com', $userAsAnArray['email']);
        self::assertSame('Y0uSh@llN0tP@ss', $userAsAnArray['password']);
    }
}
