<?php

declare(strict_types=1);

namespace App\Tests\Integration\Storage;

use App\Tests\Integration\AbstractIntegrationTestCase;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Group;

final class UserRepositoryTest extends AbstractIntegrationTestCase
{
    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function testItFindsNothingIfThereIsNoUserStored(): void
    {
        self::assertNull($this->userRepository()->findByEmail(Email::create('gandalf.thegrey@theshire.com')));
    }

    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function testICanSaveAUser(): void
    {
        $newUser = User::create(
            new UserId(),
            Email::create('gandalf.thegrey@theshire.com'),
            Password::create('Y0uSh4llN0tP4ss'),
        );

        $this->userRepository()->save($newUser);

        self::assertEquals(
            $newUser,
            $this->userRepository()->findByEmail(Email::create('gandalf.thegrey@theshire.com')),
        );
    }

    private function userRepository(): UserRepositoryInterface
    {
        /** @phpstan-var UserRepositoryInterface $repository */
        $repository = self::getContainer()->get(UserRepositoryInterface::class);

        return $repository;
    }
}
