<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserController
{
    #[Route(
        path: '/',
        name: 'create_user',
        methods: ['POST']
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(null, 200);
    }
}
