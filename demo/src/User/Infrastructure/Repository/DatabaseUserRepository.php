<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Email;
use App\User\Domain\Password;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-import-type UserRawData from User
 */
final readonly class DatabaseUserRepository implements UserRepository
{
    public function __construct(private Connection $connection) {}

    /**
     * @throws Exception
     */
    public function save(User $user): void
    {
        $userAsAnArray = $user->toArray();

        $statement = $this->connection->prepare(<<<SQL
            INSERT INTO users (id, email, password) VALUES (:id, :email, :password)
        SQL);

        $statement->bindValue('id', $userAsAnArray['id'], Types::GUID);
        $statement->bindValue('email', $userAsAnArray['email'], Types::STRING);
        $statement->bindValue('password', $userAsAnArray['password'], Types::STRING);

        $statement->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function findByEmail(Email $email): ?User
    {
        $statement = $this->connection->prepare(<<<SQL
            SELECT id, email, password FROM users WHERE email = :email
        SQL);

        $statement->bindValue('email', (string) $email, Types::STRING);

        /** @var false|UserRawData $userAsAnArray */
        $userAsAnArray = $statement->executeQuery()->fetchAssociative();

        if ($userAsAnArray === false) {
            return null;
        }

        return User::create(
            UserId::fromRawValue($userAsAnArray['id']),
            Email::create($userAsAnArray['email']),
            Password::create($userAsAnArray['password']),
        );
    }
}
