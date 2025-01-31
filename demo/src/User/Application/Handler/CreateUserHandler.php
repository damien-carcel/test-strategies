<?php

declare(strict_types=1);

namespace App\User\Application\Handler;

use App\User\Application\Command\CreateUser;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
final readonly class CreateUserHandler
{
    public function __construct(private UserRepository $userRepository) {}

    public function __invoke(CreateUser $command): UserId
    {
        $userId = UserId::create();

        $user = User::create(
            id: $userId,
            email: $command->email,
            password: $command->password,
        );

        $this->userRepository->save($user);

        return $userId;
    }
}
