<?php

declare(strict_types=1);

namespace App\User\Domain\Port;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(Email $email): ?User;
}
