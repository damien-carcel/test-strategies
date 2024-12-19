<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\User\Infrastructure\Http;

use App\Tests\EndToEnd\AbstractEndToEndTestCase;
use App\User\Domain\Email;
use App\User\Domain\UserRepository;

final class CreateUserTest extends AbstractEndToEndTestCase
{
    /**
     * @throws \JsonException
     */
    public function testICanCreateAUser(): void
    {
        $client = self::createClient();

        $client->request(
            'POST',
            '/',
            [],
            [],
            [],
            json_encode([
                'email' => 'gandalf.thegrey@theshire.com',
                'password' => 'Y0uSh4llN0tP4ss',
            ], \JSON_THROW_ON_ERROR),
        );

        $response = $client->getResponse();
        self::assertSame(201, $response->getStatusCode());

        /** @phpstan-var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);
        $user = $userRepository->findByEmail(Email::create('gandalf.thegrey@theshire.com'));
        self::assertNotNull($user);
    }
}
