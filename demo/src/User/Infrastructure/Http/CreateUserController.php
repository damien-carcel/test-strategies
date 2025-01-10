<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Http\Request\CreateUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserController
{
    public function __construct(private UserRepository $userRepository) {}

    #[Route(
        path: '/users',
        name: 'create_user',
        methods: ['POST'],
        format: 'json',
    )]
    public function __invoke(#[MapRequestPayload] CreateUserRequest $request): JsonResponse
    {
        $userId = UserId::create();
        $user = User::create(
            id: $userId,
            email: Email::create($request->email),
            password: Password::create($request->password),
        );

        $this->userRepository->save($user);

        return new JsonResponse(['user_id' => (string) $userId], Response::HTTP_CREATED);
    }
}
