<?php

declare(strict_types=1);

namespace App\User\Infrastructure\UI\Http\Request;

use App\User\Application\Command\CreateUser;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;

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
