<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\User\Domain\Email;

final class UserAlreadyExists extends \RuntimeException
{
    public static function withEmail(Email $email): self
    {
        return new self("User with email \"$email\" already exists.");
    }
}
