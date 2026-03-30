<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class ExchangeAccounts
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function list(): array { return $this->client->get('/v1/exchange_accounts'); }
    public function get(string $exchangeAccountId): array { return $this->client->get("/v1/exchange_accounts/{$exchangeAccountId}"); }
    public function getAsset(string $exchangeAccountId, string $assetId): array { return $this->client->get("/v1/exchange_accounts/{$exchangeAccountId}/{$assetId}"); }
    public function convert(string $exchangeAccountId, string $srcAsset, string $destAsset, string $amount): array {
        return $this->client->post("/v1/exchange_accounts/{$exchangeAccountId}/convert", ['srcAsset' => $srcAsset, 'destAsset' => $destAsset, 'amount' => $amount]);
    }
    public function transferToVault(string $exchangeAccountId, string $assetId, string $vaultAccountId, string $amount): array {
        return $this->client->post("/v1/exchange_accounts/{$exchangeAccountId}/{$assetId}/transfer_to_vault", ['vaultAccountId' => $vaultAccountId, 'amount' => $amount]);
    }
    public function transferFromVault(string $exchangeAccountId, string $assetId, string $vaultAccountId, string $amount): array {
        return $this->client->post("/v1/exchange_accounts/{$exchangeAccountId}/{$assetId}/transfer_from_vault", ['vaultAccountId' => $vaultAccountId, 'amount' => $amount]);
    }
    public function withdraw(string $exchangeAccountId, string $assetId, string $address, string $amount, ?string $tag = null): array {
        $data = ['address' => $address, 'amount' => $amount];
        if ($tag) $data['tag'] = $tag;
        return $this->client->post("/v1/exchange_accounts/{$exchangeAccountId}/{$assetId}/withdraw", $data);
    }
}
