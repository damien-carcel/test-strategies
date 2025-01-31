<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\User\Infrastructure\Http;

use App\Tests\EndToEnd\AbstractEndToEndTestCase;
use App\User\Domain\Email;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserControllerTest extends AbstractEndToEndTestCase
{
    public function testICanCreateAUser(): void
    {
        $client = self::createClient();

        $client->jsonRequest(
            method: 'POST',
            uri: '/users',
            parameters: [
                'email' => 'gandalf.thegrey@theshire.com',
                'password' => 'Y0uSh4llN0tP4ss',
            ],
        );

        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(201);
        $user = self::assertUserIsCreated();
        self::assertResponseContent($response, $user);
    }

    private static function assertUserIsCreated(): User
    {
        /** @phpstan-var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail(Email::create('gandalf.thegrey@theshire.com'));
        self::assertNotNull($user);

        return $user;
    }

    /**
     * @throws \JsonException
     */
    private static function assertResponseContent(Response $response, User $user): void
    {
        $responseContent = $response->getContent();
        self::assertNotFalse($responseContent);

        self::assertEqualsCanonicalizing(
            ['user_id' => $user->toArray()['id']],
            json_decode(json: $responseContent, associative: true, flags: JSON_THROW_ON_ERROR),
        );
    }
}
