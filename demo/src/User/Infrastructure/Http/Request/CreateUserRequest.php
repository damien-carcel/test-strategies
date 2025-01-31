<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Request;

use App\User\Application\Command\CreateUser;
use App\User\Domain\Email;
use App\User\Domain\Password;

final readonly class CreateUserRequest
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public function toCreateUserCommand(): CreateUser
    {
        return new CreateUser(email: Email::create($this->email), password: Password::create($this->password));
    }
}
