<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class FiatAccounts
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function list(): array { return $this->client->get('/v1/fiat_accounts'); }
    public function get(string $accountId): array { return $this->client->get("/v1/fiat_accounts/{$accountId}"); }
    public function deposit(string $accountId, string $amount): array { return $this->client->post("/v1/fiat_accounts/{$accountId}/deposit", ['amount' => $amount]); }
    public function withdraw(string $accountId, string $amount): array { return $this->client->post("/v1/fiat_accounts/{$accountId}/withdraw", ['amount' => $amount]); }
    public function redeemToDlt(string $accountId, string $amount): array { return $this->client->post("/v1/fiat_accounts/{$accountId}/redeem_to_dlt", ['amount' => $amount]); }
}
