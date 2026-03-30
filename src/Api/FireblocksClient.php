<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
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

    private function validateConfig(): void
    {
        if (empty($this->apiKey)) {
            throw new AuthenticationException('API key is required');
        }

        if (empty($this->apiSecret)) {
            throw new AuthenticationException('API secret is required');
        }
    }

    private function initializeHttpClient(): void
    {
        $stack = HandlerStack::create();

        if ($this->config['max_retries'] > 0) {
            $stack->push($this->createRetryMiddleware());
        }

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

    private function createRetryMiddleware(): callable
    {
        return Middleware::retry(
            function ($retries, $request, $response, $exception) {
                if ($retries >= $this->config['max_retries']) {
                    return false;
                }

                if ($exception instanceof RequestException && $exception->getCode() === 0) {
                    return true;
                }

                if ($response) {
                    $statusCode = $response->getStatusCode();
                    return in_array($statusCode, [408, 429, 500, 502, 503, 504], true);
                }

                return false;
            },
            function ($retries) {
                return $this->config['retry_delay'] * pow(2, $retries);
            }
        );
    }

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

    private function generateJwtToken(Request $request): string
    {
        $now = time();
        $nonce = uniqid('', true);
        
        $payload = [
            'uri' => $request->getRequestTarget(),
            'nonce' => $nonce,
            'iat' => $now,
            'exp' => $now + 60,
            'sub' => $this->apiKey,
            'bodyHash' => $this->hashBody((string) $request->getBody()),
        ];

        return JWT::encode($payload, $this->apiSecret, 'RS256');
    }

    private function hashBody(string $body): string
    {
        if (empty($body)) {
            return '';
        }
        return hash('sha256', $body);
    }

    public function get(string $path, array $params = []): array
    {
        return $this->request('GET', $path, ['query' => $params]);
    }

    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, ['json' => $data]);
    }

    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, ['json' => $data]);
    }

    public function patch(string $path, array $data = []): array
    {
        return $this->request('PATCH', $path, ['json' => $data]);
    }

    public function delete(string $path, array $params = []): array
    {
        return $this->request('DELETE', $path, ['query' => $params]);
    }

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

        switch ($statusCode) {
            case 401:
                throw new AuthenticationException($message, $statusCode);
            case 404:
                throw new NotFoundException($message, $statusCode);
            case 422:
                throw new ValidationException($message, $data['errors'] ?? [], $statusCode);
            case 429:
                throw new RateLimitException(
                    $message,
                    $statusCode,
                    (int) $response->getHeaderLine('Retry-After') ?: null
                );
            default:
                throw new FireblocksException($message, $statusCode, $errorCode, $data, $e);
        }
    }

    public function vaults(): Vaults
    {
        return $this->vaults ?? $this->vaults = new Vaults($this);
    }

    public function wallets(): Wallets
    {
        return $this->wallets ?? $this->wallets = new Wallets($this);
    }

    public function transactions(): Transactions
    {
        return $this->transactions ?? $this->transactions = new Transactions($this);
    }

    public function users(): Users
    {
        return $this->users ?? $this->users = new Users($this);
    }

    public function network(): Network
    {
        return $this->network ?? $this->network = new Network($this);
    }

    public function gasStations(): GasStations
    {
        return $this->gasStations ?? $this->gasStations = new GasStations($this);
    }

    public function contracts(): Contracts
    {
        return $this->contracts ?? $this->contracts = new Contracts($this);
    }

    public function webhooks(): Webhooks
    {
        return $this->webhooks ?? $this->webhooks = new Webhooks($this);
    }

    public function exchangeAccounts(): ExchangeAccounts
    {
        return $this->exchangeAccounts ?? $this->exchangeAccounts = new ExchangeAccounts($this);
    }

    public function fiatAccounts(): FiatAccounts
    {
        return $this->fiatAccounts ?? $this->fiatAccounts = new FiatAccounts($this);
    }
}
