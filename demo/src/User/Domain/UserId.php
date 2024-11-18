<?php

declare(strict_types=1);

namespace App\User\Domain;

use Symfony\Component\Uid\UuidV7;

final class UserId extends UuidV7
{
    public static function create(): self
    {
        return new self();
    }
}
