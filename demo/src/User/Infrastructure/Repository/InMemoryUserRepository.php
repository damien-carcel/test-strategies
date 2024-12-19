<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Email;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    public function __construct() {}

    public function save(User $user): void {}

    public function findByEmail(Email $email): ?User
    {
        return null;
    }
}
