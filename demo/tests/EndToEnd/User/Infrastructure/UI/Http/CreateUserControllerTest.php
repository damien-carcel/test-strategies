<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\User\Infrastructure\UI\Http;

use App\Tests\EndToEnd\AbstractEndToEndTestCase;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserControllerTest extends AbstractEndToEndTestCase
{
    /**
     * @throws \JsonException
     */
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

    public function testICannotCreateAUserIfAnotherOneAlreadyExistsForTheSameEmail(): void
    {
        $client = self::createClient();

        $email = 'gandalf.thegrey@theshire.com';
        $this->loadUserWithEmail($email);

        $client->jsonRequest(
            method: 'POST',
            uri: '/users',
            parameters: [
                'email' => $email,
                'password' => 'Y0uSh4llN0tP4ss',
            ],
        );

        self::assertResponseStatusCodeSame(409);
    }

    private static function assertUserIsCreated(): User
    {
        /** @phpstan-var UserRepositoryInterface $userRepository */
        $userRepository = self::getContainer()->get(UserRepositoryInterface::class);
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

    private function loadUserWithEmail(string $email): void
    {
        /** @phpstan-var UserRepositoryInterface $userRepository */
        $userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $userRepository->save(User::create(
            id: new UserId(),
            email: Email::create($email),
            password: Password::create('Y0uSh4llN0tP4ss'),
        ));
    }
}
