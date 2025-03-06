<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Handler;

use App\User\Application\Command\CreateUser;
use App\User\Application\Handler\CreateUserHandler;
use App\User\Domain\Port\MailerInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateUserHandlerTest extends TestCase
{
    private readonly MockObject&MailerInterface $mailerMock;
    private readonly MockObject&UserRepositoryInterface $userRepositoryMock;

    private readonly CreateUserHandler $createUserHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->mailerMock = $this->createMock(MailerInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $this->createUserHandler = new CreateUserHandler(
            mailer: $this->mailerMock,
            userRepository: $this->userRepositoryMock,
        );
    }

    public function testItCreatesAUser(): void
    {
        $command = new CreateUser(
            email: Email::create('gandalf.thegrey@theshire.com'),
            password: Password::create('Y0uSh4llN0tP4ss'),
        );
        $this->userRepositoryMock
            ->expects(self::once())
            ->method('findByEmail')
            ->with(self::equalTo($command->email))
            ->willReturn(null);

        $this->userRepositoryMock
            ->expects(self::once())
            ->method('save');

        ($this->createUserHandler)($command);
    }
    public function testItThrowsAnExceptionIfAUserWithTheSameEmailAlreadyExist(): void
    {
        $command = new CreateUser(
            email: Email::create('gandalf.thegrey@theshire.com'),
            password: Password::create('Y0uSh4llN0tP4ss'),
        );

        $this->userRepositoryMock
            ->expects(self::once())
            ->method('findByEmail')
            ->with(self::equalTo($command->email))
            ->willReturn(User::create(
                id: new UserId(),
                email: $command->email,
                password: $command->password,
            ));

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with email "gandalf.thegrey@theshire.com" already exists.');

        ($this->createUserHandler)($command);
    }
}
