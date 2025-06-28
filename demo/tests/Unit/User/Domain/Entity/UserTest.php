<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Entity;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
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
