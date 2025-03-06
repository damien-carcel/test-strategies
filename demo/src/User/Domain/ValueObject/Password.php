<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

final readonly class Password
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
