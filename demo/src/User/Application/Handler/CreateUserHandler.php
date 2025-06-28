<?php

declare(strict_types=1);

namespace App\User\Application\Handler;

use App\User\Application\Command\CreateUser;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class CreateUserHandler
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

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

        return $userId;
    }
}
