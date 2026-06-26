<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Fireblocks\Sdk\FireblocksServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FireblocksServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('fireblocks.api_key', 'test-api-key');
        $app['config']->set('fireblocks.api_secret', file_get_contents(__DIR__ . '/fixtures/test-private.key'));
    }
}
