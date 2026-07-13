<?php

namespace Fireblocks\Sdk\Facades;

use Illuminate\Support\Facades\Facade;
use Fireblocks\Sdk\Api\FireblocksClient;

/**
 * @method static \Fireblocks\Sdk\Api\Wallets wallets()
 * @method static \Fireblocks\Sdk\Api\Transactions transactions()
 * @method static \Fireblocks\Sdk\Api\Vaults vaults()
 * @method static \Fireblocks\Sdk\Api\Users users()
 * @method static \Fireblocks\Sdk\Api\Network network()
 * @method static \Fireblocks\Sdk\Api\GasStations gasStations()
 * @method static \Fireblocks\Sdk\Api\Contracts contracts()
 * @method static \Fireblocks\Sdk\Api\Webhooks webhooks()
 * @method static \Fireblocks\Sdk\Api\ExchangeAccounts exchangeAccounts()
 * @method static \Fireblocks\Sdk\Api\FiatAccounts fiatAccounts()
 * @method static array get(string $path, array $params = [])
 * @method static array post(string $path, array $data = [])
 * @method static array put(string $path, array $data = [])
 * @method static array patch(string $path, array $data = [])
 * @method static array delete(string $path, array $params = [])
 *
 * @see \Fireblocks\Sdk\Api\FireblocksClient
 */
class Fireblocks extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'fireblocks';
    }
}
