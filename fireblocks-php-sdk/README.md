# Fireblocks PHP Laravel SDK

[![Latest Version](https://img.shields.io/packagist/v/fireblocks/fireblocks-php-sdk.svg)](https://packagist.org/packages/fireblocks/fireblocks-php-sdk)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Official PHP SDK for the [Fireblocks](https://www.fireblocks.com/) API with full Laravel integration.

## Features

- 🔐 **JWT Authentication** - Secure request signing with RS256
- 🚀 **Laravel Native** - Service provider, facade, and config publishing
- 🔄 **Auto-Retry** - Built-in retry mechanism with exponential backoff
- 📦 **Type-Safe Models** - Full DTO coverage of Fireblocks API
- ⚡ **Modern PHP** - PHP 8.0+ with strict typing
- 🧪 **Well Tested** - PHPUnit test suite included

## Installation

```bash
composer require fireblocks/fireblocks-php-sdk
```

## Laravel Setup

### 1. Publish Configuration

```bash
php artisan vendor:publish --tag=fireblocks-config
```

### 2. Configure Environment Variables

```env
FIREBLOCKS_BASE_URL=https://api.fireblocks.io
FIREBLOCKS_API_KEY=your-api-key
FIREBLOCKS_API_SECRET=-----BEGIN RSA PRIVATE KEY-----
...
-----END RSA PRIVATE KEY-----
```

Or use a secret file:

```env
FIREBLOCKS_API_SECRET_PATH=/path/to/private.key
```

### 3. Optional: Use the Facade

Add to `config/app.php` (Laravel 10 and below):

```php
'aliases' => [
    // ...
    'Fireblocks' => Fireblocks\Sdk\Facades\Fireblocks::class,
]
```

## Usage

### Using Dependency Injection

```php
use Fireblocks\Sdk\Api\FireblocksClient;

class WalletController extends Controller
{
    public function index(FireblocksClient $fireblocks)
    {
        // List vault accounts
        $accounts = $fireblocks->vaults()->listAccounts();
        
        // Get specific account
        $account = $fireblocks->vaults()->getAccount('0');
        
        return response()->json($accounts);
    }
}
```

### Using the Facade

```php
use Fireblocks;

// List all vault accounts
$accounts = Fireblocks::vaults()->listAccounts();

// Create a new transaction
use Fireblocks\Sdk\Models\TransactionRequest;

$request = new TransactionRequest();
$request
    ->withAsset('BTC')
    ->withAmount('0.1')
    ->fromVaultAccount('0')
    ->toOneTimeAddress('bc1q...')
    ->withNote('Test transaction');

$transaction = Fireblocks::transactions()->create($request);
```

### Manual Instantiation

```php
use Fireblocks\Sdk\Api\FireblocksClient;

$client = new FireblocksClient([
    'base_url' => 'https://api.fireblocks.io',
    'api_key' => 'your-api-key',
    'api_secret' => file_get_contents('/path/to/private.key'),
    'timeout' => 60,
]);

$accounts = $client->vaults()->listAccounts();
```

## API Reference

### Vaults

```php
// List vault accounts
$accounts = $fireblocks->vaults()->listAccounts(['namePrefix' => 'Main']);

// Create vault account
use Fireblocks\Sdk\Models\CreateVaultAccountRequest;
$request = new CreateVaultAccountRequest(['name' => 'New Account']);
$account = $fireblocks->vaults()->createAccount($request);

// Get vault account assets
$assets = $fireblocks->vaults()->getAsset('0', 'BTC');

// Create deposit address
$address = $fireblocks->vaults()->createDepositAddress('0', 'BTC', 'Main Address');
```

### Transactions

```php
// Create a transaction
$request = new TransactionRequest();
$request
    ->withAsset('ETH')
    ->withAmount('1.5')
    ->fromVaultAccount('0')
    ->toVaultAccount('1')
    ->withFeeLevel('HIGH');

$transaction = $fireblocks->transactions()->create($request);

// Get transaction status
$tx = $fireblocks->transactions()->get($transaction->id);

// List transactions
$transactions = $fireblocks->transactions()->list([
    'status' => 'COMPLETED',
    'after' => '2024-01-01T00:00:00Z',
]);

// Cancel a transaction
$fireblocks->transactions()->cancel($transaction->id, 'User requested');
```

### Wallets

```php
// Internal wallets
$wallets = $fireblocks->wallets()->listInternalWallets();
$fireblocks->wallets()->createInternalWallet('My Wallet', 'ref-123');

// External wallets
$wallets = $fireblocks->wallets()->listExternalWallets();

// Contract wallets
$wallets = $fireblocks->wallets()->listContractWallets();
```

### Exchange Accounts

```php
$exchanges = $fireblocks->exchangeAccounts()->list();
$assets = $fireblocks->exchangeAccounts()->getAsset('exchange-id', 'BTC');
$fireblocks->exchangeAccounts()->convert('exchange-id', 'BTC', 'USD', '1.0');
```

### Webhooks

```php
$webhooks = $fireblocks->webhooks()->list();
$fireblocks->webhooks()->create('https://example.com/webhook', ['TRANSACTION_CREATED', 'TRANSACTION_COMPLETED']);
```

### Gas Stations

```php
$config = $fireblocks->gasStations()->getConfiguration();
$fireblocks->gasStations()->setConfiguration(['maxGasPrice' => '100']);
```

## Transaction Request Builder

The SDK provides a fluent builder for creating transactions:

```php
use Fireblocks\Sdk\Models\TransactionRequest;

// Transfer between vault accounts
$request = (new TransactionRequest())
    ->withAsset('BTC')
    ->withAmount('0.5')
    ->fromVaultAccount('source-vault-id')
    ->toVaultAccount('destination-vault-id')
    ->withNote('Internal transfer')
    ->withExternalTxId('external-ref-123');

// Transfer to external address
$request = (new TransactionRequest())
    ->withAsset('ETH')
    ->withAmount('1.0')
    ->fromVaultAccount('0')
    ->toOneTimeAddress('0x...', 'memo-tag') // optional tag
    ->withFeeLevel('HIGH');

// Transfer to exchange
$request = (new TransactionRequest())
    ->withAsset('USDC')
    ->withAmount('1000')
    ->fromVaultAccount('0')
    ->toExchange('exchange-account-id');
```

## Error Handling

```php
use Fireblocks\Sdk\Exceptions\FireblocksException;
use Fireblocks\Sdk\Exceptions\AuthenticationException;
use Fireblocks\Sdk\Exceptions\ValidationException;
use Fireblocks\Sdk\Exceptions\NotFoundException;
use Fireblocks\Sdk\Exceptions\RateLimitException;

try {
    $transaction = $fireblocks->transactions()->create($request);
} catch (ValidationException $e) {
    // Handle validation errors
    $errors = $e->getErrors();
} catch (NotFoundException $e) {
    // Resource not found
} catch (RateLimitException $e) {
    // Rate limited - check $e->getRetryAfter()
    sleep($e->getRetryAfter());
} catch (AuthenticationException $e) {
    // Invalid API key or secret
} catch (FireblocksException $e) {
    // Generic Fireblocks error
    $errorCode = $e->getErrorCode();
    $errorData = $e->getErrorData();
}
```

## Webhook Handling

```php
use Fireblocks\Sdk\Api\FireblocksClient;

class WebhookController extends Controller
{
    public function handle(Request $request, FireblocksClient $fireblocks)
    {
        // Verify webhook signature
        $publicKey = $fireblocks->webhooks()->getPublicKey()['publicKey'];
        
        // Validate signature...
        
        $event = $request->all();
        
        match ($event['type']) {
            'TRANSACTION_CREATED' => $this->handleTransactionCreated($event),
            'TRANSACTION_COMPLETED' => $this->handleTransactionCompleted($event),
            'TRANSACTION_FAILED' => $this->handleTransactionFailed($event),
            default => null,
        };
        
        return response()->noContent();
    }
}
```

## Configuration Options

```php
// config/fireblocks.php
return [
    'base_url' => env('FIREBLOCKS_BASE_URL', 'https://api.fireblocks.io'),
    'api_key' => env('FIREBLOCKS_API_KEY'),
    'api_secret' => env('FIREBLOCKS_API_SECRET'),
    'api_secret_path' => env('FIREBLOCKS_API_SECRET_PATH'),
    'timeout' => 30,
    'connect_timeout' => 10,
    'max_retries' => 3,
    'retry_delay' => 500,
    'debug' => false,
    'proxy' => null,
];
```

## Testing

```bash
# Run tests
composer test

# Run static analysis
composer phpstan

# Run code style checks
composer phpcs

# Fix code style
composer phpcbf
```

## Advanced Usage

### Custom HTTP Configuration

```php
$client = new FireblocksClient([
    'api_key' => 'key',
    'api_secret' => 'secret',
    'timeout' => 60,
    'connect_timeout' => 10,
    'max_retries' => 5,
    'retry_delay' => 1000,
    'proxy' => 'http://proxy:8080',
    'headers' => ['X-Custom-Header' => 'Value'],
]);
```

### Raw API Access

```php
// Direct API access if needed
$response = $fireblocks->get('/v1/vault/accounts');
$response = $fireblocks->post('/v1/transactions', [...]);
$response = $fireblocks->put('/v1/vault/accounts/0', [...]);
$response = $fireblocks->patch('/v1/webhooks/123', [...]);
$response = $fireblocks->delete('/v1/internal_wallets/123');
```

## License

This SDK is licensed under the MIT License. See [LICENSE](LICENSE) for details.

## Support

- [Fireblocks Documentation](https://developers.fireblocks.com/)
- [Fireblocks API Reference](https://docs.fireblocks.com/api/)
- [GitHub Issues](https://github.com/fireblocks/php-sdk/issues)

## Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) before submitting PRs.
