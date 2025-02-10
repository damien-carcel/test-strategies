<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;

final readonly class CreateUser
{
    public function __construct(public Email $email, public Password $password) {}
}
