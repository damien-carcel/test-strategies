<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class BaseTest extends TestCase
{
    public function testNothingReal(): void
    {
        self::expectNotToPerformAssertions();
    }
}
