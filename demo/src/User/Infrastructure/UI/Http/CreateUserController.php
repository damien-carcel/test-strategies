<?php

declare(strict_types=1);

namespace App\User\Infrastructure\UI\Http;

use App\User\Application\Handler\CreateUserHandler;
use App\User\Domain\Exception\UserAlreadyExists;
use App\User\Infrastructure\UI\Http\Request\CreateUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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
        try {
            $userId = ($this->createUserHandler)($request->toCreateUserCommand());
        } catch (UserAlreadyExists $exception) {
            throw new ConflictHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse(['user_id' => (string) $userId], Response::HTTP_CREATED);
    }
}
