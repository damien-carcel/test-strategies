<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use App\User\Domain\Email;
use App\User\Domain\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserController
{
    public function __construct(private UserRepository $userRepository) {}

    #[Route(
        path: '/',
        name: 'create_user',
        methods: ['POST'],
    )]
    public function __invoke(): JsonResponse
    {
        $user = $this->userRepository->findByEmail(Email::create(''));

        return new JsonResponse($user, 200);
    }
}
