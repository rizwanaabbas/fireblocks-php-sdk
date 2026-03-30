<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use Fireblocks\Sdk\Exceptions\AuthenticationException;
use Fireblocks\Sdk\Exceptions\FireblocksException;
use Fireblocks\Sdk\Exceptions\NotFoundException;
use Fireblocks\Sdk\Exceptions\RateLimitException;
use Fireblocks\Sdk\Exceptions\ValidationException;

class FireblocksClient
{
    private Client $httpClient;
    private string $baseUrl;
    private string $apiKey;
    private string $apiSecret;
    private array $config;
    
    // API endpoint handlers
    private ?Vaults $vaults = null;
    private ?Wallets $wallets = null;
    private ?Transactions $transactions = null;
    private ?Users $users = null;
    private ?Network $network = null;
    private ?GasStations $gasStations = null;
    private ?Contracts $contracts = null;
    private ?Webhooks $webhooks = null;
    private ?ExchangeAccounts $exchangeAccounts = null;
    private ?FiatAccounts $fiatAccounts = null;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'base_url' => 'https://api.fireblocks.io',
            'timeout' => 30,
            'connect_timeout' => 10,
            'max_retries' => 3,
            'retry_delay' => 500,
            'debug' => false,
            'proxy' => null,
            'headers' => [],
        ], $config);

        $this->baseUrl = rtrim($this->config['base_url'], '/');
        $this->apiKey = $this->config['api_key'] ?? '';
        $this->apiSecret = $this->loadApiSecret();

        $this->validateConfig();
        $this->initializeHttpClient();
    }

    /**
     * Load API secret from config or file.
     */
    private function loadApiSecret(): string
    {
        if (!empty($this->config['api_secret'])) {
            return $this->config['api_secret'];
        }

        if (!empty($this->config['api_secret_path']) && file_exists($this->config['api_secret_path'])) {
            return file_get_contents($this->config['api_secret_path']);
        }

        return '';
    }

    /**
     * Validate configuration.
     */
    private function validateConfig(): void
    {
        if (empty($this->apiKey)) {
            throw new AuthenticationException('API key is required');
        }

        if (empty($this->apiSecret)) {
            throw new AuthenticationException('API secret is required');
        }
    }

    /**
     * Initialize HTTP client with middleware.
     */
    private function initializeHttpClient(): void
    {
        $stack = HandlerStack::create();

        // Add retry middleware
        if ($this->config['max_retries'] > 0) {
            $stack->push($this->createRetryMiddleware());
        }

        // Add auth middleware
        $stack->push($this->createAuthMiddleware());

        $options = [
            'base_uri' => $this->baseUrl,
            'timeout' => $this->config['timeout'],
            'connect_timeout' => $this->config['connect_timeout'],
            'handler' => $stack,
            'headers' => array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ], $this->config['headers']),
        ];

        if ($this->config['debug']) {
            $options['debug'] = true;
        }

        if ($this->config['proxy']) {
            $options['proxy'] = $this->config['proxy'];
        }

        $this->httpClient = new Client($options);
    }

    /**
     * Create retry middleware.
     */
    private function createRetryMiddleware(): callable
    {
        return Middleware::retry(
            function ($retries, $request, $response, $exception) {
                // Don't retry if we've hit the limit
                if ($retries >= $this->config['max_retries']) {
                    return false;
                }

                // Retry on connection errors
                if ($exception instanceof RequestException && $exception->getCode() === 0) {
                    return true;
                }

                // Retry on specific HTTP status codes
                if ($response) {
                    $statusCode = $response->getStatusCode();
                    // Retry on 5xx errors, 429 (rate limit), and 408 (timeout)
                    return in_array($statusCode, [408, 429, 500, 502, 503, 504], true);
                }

                return false;
            },
            function ($retries) {
                return $this->config['retry_delay'] * pow(2, $retries); // Exponential backoff
            }
        );
    }

    /**
     * Create authentication middleware for JWT.
     */
    private function createAuthMiddleware(): callable
    {
        return function (callable $handler) {
            return function (Request $request, array $options) use ($handler) {
                $token = $this->generateJwtToken($request);
                
                $request = $request->withHeader('Authorization', "Bearer {$token}");
                $request = $request->withHeader('X-API-Key', $this->apiKey);

                return $handler($request, $options);
            };
        };
    }

    /**
     * Generate JWT token for request.
     */
    private function generateJwtToken(Request $request): string
    {
        $now = time();
        $nonce = uniqid('', true);
        
        $payload = [
            'uri' => $request->getRequestTarget(),
            'nonce' => $nonce,
            'iat' => $now,
            'exp' => $now + 60, // Token valid for 60 seconds
            'sub' => $this->apiKey,
            'bodyHash' => $this->hashBody((string) $request->getBody()),
        ];

        return JWT::encode($payload, $this->apiSecret, 'RS256');
    }

    /**
     * Hash request body for JWT.
     */
    private function hashBody(string $body): string
    {
        if (empty($body)) {
            return '';
        }
        return hash('sha256', $body);
    }

    /**
     * Make GET request.
     */
    public function get(string $path, array $params = []): array
    {
        return $this->request('GET', $path, ['query' => $params]);
    }

    /**
     * Make POST request.
     */
    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, ['json' => $data]);
    }

    /**
     * Make PUT request.
     */
    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, ['json' => $data]);
    }

    /**
     * Make PATCH request.
     */
    public function patch(string $path, array $data = []): array
    {
        return $this->request('PATCH', $path, ['json' => $data]);
    }

    /**
     * Make DELETE request.
     */
    public function delete(string $path, array $params = []): array
    {
        return $this->request('DELETE', $path, ['query' => $params]);
    }

    /**
     * Make HTTP request.
     */
    private function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $path, $options);
            $body = (string) $response->getBody();
            
            if (empty($body)) {
                return [];
            }

            $data = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new FireblocksException('Invalid JSON response: ' . json_last_error_msg());
            }

            return $data;
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Handle request exception.
     */
    private function handleRequestException(RequestException $e): void
    {
        $response = $e->getResponse();
        
        if (!$response) {
            throw new FireblocksException(
                'Network error: ' . $e->getMessage(),
                0,
                null,
                null,
                $e
            );
        }

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true) ?? [];
        
        $message = $data['message'] ?? $e->getMessage();
        $errorCode = $data['code'] ?? null;

        match ($statusCode) {
            401 => throw new AuthenticationException($message, $statusCode),
            404 => throw new NotFoundException($message, $statusCode),
            422 => throw new ValidationException($message, $data['errors'] ?? [], $statusCode),
            429 => throw new RateLimitException(
                $message,
                $statusCode,
                (int) $response->getHeaderLine('Retry-After') ?: null
            ),
            default => throw new FireblocksException($message, $statusCode, $errorCode, $data, $e),
        };
    }

    // ==================== API Endpoint Accessors ====================

    public function vaults(): Vaults
    {
        return $this->vaults ??= new Vaults($this);
    }

    public function wallets(): Wallets
    {
        return $this->wallets ??= new Wallets($this);
    }

    public function transactions(): Transactions
    {
        return $this->transactions ??= new Transactions($this);
    }

    public function users(): Users
    {
        return $this->users ??= new Users($this);
    }

    public function network(): Network
    {
        return $this->network ??= new Network($this);
    }

    public function gasStations(): GasStations
    {
        return $this->gasStations ??= new GasStations($this);
    }

    public function contracts(): Contracts
    {
        return $this->contracts ??= new Contracts($this);
    }

    public function webhooks(): Webhooks
    {
        return $this->webhooks ??= new Webhooks($this);
    }

    public function exchangeAccounts(): ExchangeAccounts
    {
        return $this->exchangeAccounts ??= new ExchangeAccounts($this);
    }

    public function fiatAccounts(): FiatAccounts
    {
        return $this->fiatAccounts ??= new FiatAccounts($this);
    }
}
