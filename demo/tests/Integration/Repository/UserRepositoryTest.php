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
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = self::getContainer()->get(UserRepository::class);
    }

    /**
     * @group with-in-memory-adapters
     * @group with-production-adapters
     */
    public function testItFindsNothingIfThereIsNoUserStored()
    {
        self::assertNull($this->repository->findByEmail(Email::create('gandalf.thegrey@theshire.com')));
    }
}
