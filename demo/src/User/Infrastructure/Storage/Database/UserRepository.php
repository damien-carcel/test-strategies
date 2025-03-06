<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Storage\Database;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Port\UserRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-import-type UserRawData from User
 */
final readonly class UserRepository implements UserRepositoryInterface
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
