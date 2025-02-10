<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Handler;

use App\User\Application\Command\CreateUser;
use App\User\Application\Handler\CreateUserHandler;
use App\User\Domain\Email;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateUserHandlerTest extends TestCase
{
    private readonly MockObject&UserRepository $userRepositoryMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
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

        new CreateUserHandler($this->userRepositoryMock)($command);
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

        new CreateUserHandler($this->userRepositoryMock)($command);
    }
}
