<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer\InMemory;

use App\User\Domain\Port\MailerInterface;
use App\User\Domain\ValueObject\Email;

final readonly class Mailer implements MailerInterface
{
    public function send(Email $email, string $subject, string $body): void
    {
        // TODO: Implement send() method.
    }
}
