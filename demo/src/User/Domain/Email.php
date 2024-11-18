<?php

declare(strict_types=1);

namespace App\User\Domain;

final readonly class Email
{
    private function __construct(private string $value) {}

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
