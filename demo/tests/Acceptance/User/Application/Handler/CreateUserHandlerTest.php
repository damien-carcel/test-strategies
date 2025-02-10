<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\User\Application\Handler;

use App\Tests\Acceptance\AbstractAcceptanceTestCase;
use App\User\Application\Command\CreateUser;
use App\User\Application\Handler\CreateUserHandler;
use App\User\Domain\Email;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;

final class CreateUserHandlerTest extends AbstractAcceptanceTestCase
{
    private CreateUserHandler $createUserHandler;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @phpstan-var CreateUserHandler $createUserHandler */
        $createUserHandler = self::getContainer()->get(CreateUserHandler::class);
        /** @phpstan-var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        $this->createUserHandler = $createUserHandler;
        $this->userRepository = $userRepository;
    }

    public function testItCreatesAUser(): void
    {
        $command = new CreateUser(
            email: Email::create('gandalf.thegrey@theshire.com'),
            password: Password::create('Y0uSh4llN0tP4ss'),
        );

        $userId = ($this->createUserHandler)($command);

        $storedUser = $this->assertUserIsCreated();
        self::assertSame($storedUser->toArray()['id'], (string) $userId->toString());
    }

    public function testItThrowsAnExceptionIfAUserWithTheSameEmailAlreadyExist(): void
    {
        $email = 'gandalf.thegrey@theshire.com';
        $this->loadUserWithEmail($email);

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with email "gandalf.thegrey@theshire.com" already exists.');

        ($this->createUserHandler)(new CreateUser(
            email: Email::create($email),
            password: Password::create('Y0uSh4llN0tP4ss'),
        ));
    }

    private function assertUserIsCreated(): User
    {
        $user = $this->userRepository->findByEmail(Email::create('gandalf.thegrey@theshire.com'));
        self::assertNotNull($user);

        $userAsAnArray = $user->toArray();
        self::assertSame('gandalf.thegrey@theshire.com', $userAsAnArray['email']);
        self::assertSame('Y0uSh4llN0tP4ss', $userAsAnArray['password']);

        return $user;
    }

    private function loadUserWithEmail(string $email): void
    {
        $this->userRepository->save(User::create(
            id: new UserId(),
            email: Email::create($email),
            password: Password::create('Y0uSh4llN0tP4ss'),
        ));
    }
}
