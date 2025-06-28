<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\ValueObject\UserId;

/**
 * @phpstan-type UserRawData array{id: string, email: string, password: string}
 */
final readonly class User
{
    private function __construct(
        private UserId $id,
        private Email $email,
        private Password $password,
    ) {}

    public static function create(UserId $id, Email $email, Password $password): self
    {
        return new self($id, $email, $password);
    }

    /**
     * @phpstan-return UserRawData
     */
    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'email' => (string) $this->email,
            'password' => (string) $this->password,
        ];
    }
}
