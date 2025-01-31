<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use App\User\Application\Handler\CreateUserHandler;
use App\User\Infrastructure\Http\Request\CreateUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserController
{
    public function __construct(private CreateUserHandler $createUserHandler) {}

    #[Route(
        path: '/users',
        name: 'create_user',
        methods: ['POST'],
        format: 'json',
    )]
    public function __invoke(#[MapRequestPayload] CreateUserRequest $request): JsonResponse
    {
        $userId = ($this->createUserHandler)($request->toCreateUserCommand());

        return new JsonResponse(['user_id' => (string) $userId], Response::HTTP_CREATED);
    }
}
