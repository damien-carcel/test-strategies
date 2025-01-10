<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Request;

final readonly class CreateUserRequest
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
