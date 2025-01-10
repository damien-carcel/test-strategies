<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;

/**
 * @phpstan-import-type UserRawData from User
 */
final class InMemoryUserRepository implements UserRepository
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
