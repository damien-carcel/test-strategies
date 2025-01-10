<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\AbstractIntegrationTestCase;
use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;

final class UserRepositoryTest extends AbstractIntegrationTestCase
{
    /**
     * @group with-in-memory-adapters
     * @group with-production-adapters
     */
    public function testItFindsNothingIfThereIsNoUserStored(): void
    {
        self::assertNull($this->userRepository()->findByEmail(Email::create('gandalf.thegrey@theshire.com')));
    }

    /**
     * @group with-in-memory-adapters
     * @group with-production-adapters
     */
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

    private function userRepository(): UserRepository
    {
        /** @phpstan-var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);

        return $repository;
    }
}
