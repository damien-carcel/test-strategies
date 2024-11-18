<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\User\Infrastructure\Http;

use App\Tests\EndToEnd\AbstractEndToEndTestCase;

final class CreateUserTest extends AbstractEndToEndTestCase
{
    public function testICanCreateAUser(): void
    {
        $client = self::createClient();

        $client->request(
            'POST',
            '/',
            [],
            [],
            [],
            json_encode(['email' => 'gandalf.thegrey@theshire.com'], \JSON_THROW_ON_ERROR),
        );

        $response = $client->getResponse();
        self::assertSame(200, $response->getStatusCode());
    }
}
