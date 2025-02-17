<?php

declare(strict_types=1);

namespace App\User\Domain\Port;

use App\User\Domain\ValueObject\Email;

interface MailerInterface
{
    public function send(Email $email, string $subject, string $body): void;
}
