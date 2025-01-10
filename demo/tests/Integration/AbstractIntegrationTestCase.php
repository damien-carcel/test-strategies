<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractIntegrationTestCase extends KernelTestCase
{
    /**
     * @param array<string, string> $options
     */
    protected static function bootKernel(array $options = []): KernelInterface
    {
        /** @phpstan-var string $testEnvironment */
        $testEnvironment = self::getCurrentTestEnvironment();

        if (!\in_array($testEnvironment, ['memory', 'test'], true)) {
            throw new \RuntimeException(
                "Invalid TEST_ENV environment variable value \"$testEnvironment\". Valid values are \"in-memory\" and \"test\".",
            );
        }

        $options = array_merge($options, ['environment' => $testEnvironment]);

        return parent::bootKernel($options);
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setup();
        $testEnvironment = self::getCurrentTestEnvironment();

        if ('memory' === $testEnvironment) {
            /** @var Connection $databaseConnection */
            $databaseConnection = self::getContainer()->get('doctrine.dbal.default_connection');
            $databaseConnection->executeStatement('TRUNCATE TABLE "users"');
        }
    }

    private static function getCurrentTestEnvironment(): string
    {
        /** @phpstan-var string $testEnvironment */
        $testEnvironment = $_ENV['TEST_ENV'] ?? throw new \RuntimeException('TEST_ENV environment variable not found.');

        return $testEnvironment;
    }
}
