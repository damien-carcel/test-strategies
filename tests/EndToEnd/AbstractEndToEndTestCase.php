<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractEndToEndTestCase extends WebTestCase
{
    protected static function bootKernel(array $options = []): KernelInterface
    {
        $options = array_merge($options, ['environment' => 'test']);

        return parent::bootKernel($options);
    }
}
