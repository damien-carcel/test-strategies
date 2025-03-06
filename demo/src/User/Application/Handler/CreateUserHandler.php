<?php

declare(strict_types=1);

namespace App\User\Application\Handler;

use App\User\Application\Command\CreateUser;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Domain\Entity\User;
use App\User\Domain\Port\MailerInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class CreateUserHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(CreateUser $command): UserId
    {
        $userId = UserId::create();

        if (null !== $this->userRepository->findByEmail($command->email)) {
            throw UserAlreadyExists::withEmail($command->email);
        }

        $user = User::create(
            id: $userId,
            email: $command->email,
            password: $command->password,
        );

        $this->userRepository->save($user);

        // TODO: send email
        //       Use a port/adapter => in-memory fake implem for acceptance tests,
        //       symfony base implem to be tested in controller integration tests.

        return $userId;
    }
}
