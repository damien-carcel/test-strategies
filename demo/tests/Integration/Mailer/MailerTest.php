<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mailer;

use App\Tests\Integration\AbstractIntegrationTestCase;
use App\User\Domain\Port\MailerInterface;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\Attributes\Group;

final class MailerTest extends AbstractIntegrationTestCase
{
    private readonly MailerInterface $mailer;
    protected function setUp(): void
    {
        parent::setUp();

        $this->mailer = self::getContainer()->get(MailerInterface::class);
    }

    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function testItCanSendAnEmail(): void
    {
        $this->mailer->send(
            email: Email::create('gandalf.thegrey@theshire.com'),
            subject: 'Welcome to Mordor',
            body: 'Fly, you fools!',
        );

        self::expectNotToPerformAssertions();
    }
}
