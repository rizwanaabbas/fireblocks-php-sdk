<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Facades;

use Illuminate\Support\Facades\Facade;
use Fireblocks\Sdk\Api\FireblocksClient;

/**
 * @method static \Fireblocks\Sdk\Api\Wallets getWallets()
 * @method static \Fireblocks\Sdk\Api\Transactions getTransactions()
 * @method static \Fireblocks\Sdk\Api\Vaults getVaults()
 * @method static \Fireblocks\Sdk\Api\Users getUsers()
 * @method static \Fireblocks\Sdk\Api\Network getNetwork()
 * @method static \Fireblocks\Sdk\Api\GasStations getGasStations()
 * @method static \Fireblocks\Sdk\Api\Contracts getContracts()
 * @method static \Fireblocks\Sdk\Api\Webhooks getWebhooks()
 * @method static \Fireblocks\Sdk\Api\ExchangeAccounts getExchangeAccounts()
 * @method static \Fireblocks\Sdk\Api\FiatAccounts getFiatAccounts()
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
    protected static function getFacadeAccessor(): string
    {
        return 'fireblocks';
    }
}
