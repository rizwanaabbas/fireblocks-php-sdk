<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class Wallets
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    // Internal Wallets
    public function listInternalWallets(): array { return $this->client->get('/v1/internal_wallets'); }
    public function getInternalWallet(string $walletId): array { return $this->client->get("/v1/internal_wallets/{$walletId}"); }
    public function createInternalWallet(string $name, ?string $customerRefId = null): array { return $this->client->post('/v1/internal_wallets', ['name' => $name, 'customerRefId' => $customerRefId]); }
    public function deleteInternalWallet(string $walletId): void { $this->client->delete("/v1/internal_wallets/{$walletId}"); }
    public function addInternalWalletAsset(string $walletId, string $assetId, string $address, ?string $tag = null): array { return $this->client->post("/v1/internal_wallets/{$walletId}/{$assetId}", ['address' => $address, 'tag' => $tag]); }
    public function deleteInternalWalletAsset(string $walletId, string $assetId): void { $this->client->delete("/v1/internal_wallets/{$walletId}/{$assetId}"); }
    
    // External Wallets
    public function listExternalWallets(): array { return $this->client->get('/v1/external_wallets'); }
    public function getExternalWallet(string $walletId): array { return $this->client->get("/v1/external_wallets/{$walletId}"); }
    public function createExternalWallet(string $name, ?string $customerRefId = null): array { return $this->client->post('/v1/external_wallets', ['name' => $name, 'customerRefId' => $customerRefId]); }
    public function deleteExternalWallet(string $walletId): void { $this->client->delete("/v1/external_wallets/{$walletId}"); }
    public function addExternalWalletAsset(string $walletId, string $assetId, string $address, ?string $tag = null): array { return $this->client->post("/v1/external_wallets/{$walletId}/{$assetId}", ['address' => $address, 'tag' => $tag]); }
    public function deleteExternalWalletAsset(string $walletId, string $assetId): void { $this->client->delete("/v1/external_wallets/{$walletId}/{$assetId}"); }
    
    // Contract Wallets
    public function listContractWallets(): array { return $this->client->get('/v1/contracts'); }
    public function getContractWallet(string $walletId): array { return $this->client->get("/v1/contracts/{$walletId}"); }
    public function createContractWallet(string $name): array { return $this->client->post('/v1/contracts', ['name' => $name]); }
    public function deleteContractWallet(string $walletId): void { $this->client->delete("/v1/contracts/{$walletId}"); }
    public function addContractWalletAsset(string $walletId, string $assetId, string $address): array { return $this->client->post("/v1/contracts/{$walletId}/{$assetId}", ['address' => $address]); }
    public function deleteContractWalletAsset(string $walletId, string $assetId): void { $this->client->delete("/v1/contracts/{$walletId}/{$assetId}"); }
}
