<?php


declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\AbstractIntegrationTestCase;
use App\User\Domain\UserRepository;

final class UserRepositoryTest extends AbstractIntegrationTestCase
{
    /**
     * @group with-in-memory-adapters
     * @group with-production-adapters
     */
    public function testICanCreateAUser(): void
    {
        $repository = self::getContainer()->get(UserRepository::class);

        self::assertInstanceOf(UserRepository::class, $repository);
    }
}
