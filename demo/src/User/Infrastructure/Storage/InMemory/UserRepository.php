<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Storage\InMemory;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;

/**
 * @phpstan-import-type UserRawData from User
 */
final class UserRepository implements UserRepositoryInterface
{
    /** @var UserRawData[] */
    private array $users = [];

    public function save(User $user): void
    {
        $userAsAnArray = $user->toArray();
        $this->users[$userAsAnArray['email']] = $userAsAnArray;
    }

    public function findByEmail(Email $email): ?User
    {
        $userAsAnArray = $this->users[(string) $email] ?? null;

        if ($userAsAnArray === null) {
            return null;
        }

        return User::create(
            UserId::fromRawValue($userAsAnArray['id']),
            Email::create($userAsAnArray['email']),
            Password::create($userAsAnArray['password']),
        );
    }
}
