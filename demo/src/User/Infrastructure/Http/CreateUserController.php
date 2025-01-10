<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserController
{
    public function __construct(private UserRepository $userRepository) {}

    #[Route(
        path: '/',
        name: 'create_user',
        methods: ['POST'],
    )]
    public function __invoke(Request $request): JsonResponse
    {
        /** @var string $email */
        $email = $request->getPayload()->get('email');
        /** @var string $password */
        $password = $request->getPayload()->get('password');

        $userId = UserId::create();
        $user = User::create(
            id: $userId,
            email: Email::create($email),
            password: Password::create($password),
        );

        $this->userRepository->save($user);

        return new JsonResponse(['user_id' => (string) $userId], Response::HTTP_CREATED);
    }
}
