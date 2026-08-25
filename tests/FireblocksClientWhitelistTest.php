<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Tests;

use Fireblocks\Sdk\Api\FireblocksClient;
use Fireblocks\Sdk\Exceptions\WhitelistCredentialsNotConfiguredException;

class FireblocksClientWhitelistTest extends TestCase
{
    public function test_for_whitelist_address_throws_when_credentials_missing(): void
    {
        $client = new FireblocksClient([
            'api_key' => 'admin-key',
            'api_secret' => file_get_contents(__DIR__ . '/fixtures/test-private.key'),
        ]);

        $this->expectException(WhitelistCredentialsNotConfiguredException::class);

        $client->forWhitelistAddress();
    }

    public function test_for_whitelist_address_returns_client_with_overridden_credentials(): void
    {
        $secretPath = __DIR__ . '/fixtures/test-private.key';

        $client = new FireblocksClient([
            'api_key' => 'admin-key',
            'api_secret_path' => $secretPath,
            'whitelist_api_key' => 'whitelist-key',
            'whitelist_api_secret_path' => $secretPath,
        ]);

        $whitelistClient = $client->forWhitelistAddress();

        $this->assertNotSame($client, $whitelistClient);
    }
}
