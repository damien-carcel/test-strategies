<?php

declare(strict_types=1);

namespace App\User\Domain;

final readonly class User
{
    public function __construct(
        private string $id,
        private string $name,
        private string $email,
        private string $password,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
