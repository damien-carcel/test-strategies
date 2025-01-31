<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\User\Domain\Email;
use App\User\Domain\Password;

final readonly class CreateUser
{
    public function __construct(public Email $email, public Password $password) {}
}
