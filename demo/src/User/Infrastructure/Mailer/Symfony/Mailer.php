<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer\Symfony;

use App\User\Domain\Port\MailerInterface;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;

final readonly class Mailer implements MailerInterface
{
    public function __construct(private SymfonyMailer $mailer) {}

    public function send(Email $email, string $subject, string $body): void
    {
        $this->mailer->send();
    }
}
