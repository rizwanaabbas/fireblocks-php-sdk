<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

use Fireblocks\Sdk\Models\VaultAccount;
use Fireblocks\Sdk\Models\VaultAsset;
use Fireblocks\Sdk\Models\DepositAddress;
use Fireblocks\Sdk\Models\CreateVaultAccountRequest;
use Fireblocks\Sdk\Models\CreateVaultAssetRequest;

class Vaults
{
    private FireblocksClient $client;

    public function __construct(FireblocksClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all vault accounts.
     *
     * @param array $params Query parameters (namePrefix, nameSuffix, minAmountThreshold, etc.)
     * @return array<VaultAccount>
     */
    public function listAccounts(array $params = []): array
    {
        $response = $this->client->get('/v1/vault/accounts_paged', $params);
        
        return array_map(fn ($item) => new VaultAccount($item), $response['accounts'] ?? []);
    }

    /**
     * Get a specific vault account.
     */
    public function getAccount(string $vaultAccountId): VaultAccount
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}");
        
        return new VaultAccount($response);
    }

    /**
     * Create a new vault account.
     */
    public function createAccount(CreateVaultAccountRequest $request): VaultAccount
    {
        $response = $this->client->post('/v1/vault/accounts', $request->toArray());
        
        return new VaultAccount($response);
    }

    /**
     * Update vault account name.
     */
    public function updateAccount(string $vaultAccountId, string $name): VaultAccount
    {
        $response = $this->client->put("/v1/vault/accounts/{$vaultAccountId}", ['name' => $name]);
        
        return new VaultAccount($response);
    }

    /**
     * Hide a vault account.
     */
    public function hideAccount(string $vaultAccountId): void
    {
        $this->client->post("/v1/vault/accounts/{$vaultAccountId}/hide");
    }

    /**
     * Unhide a vault account.
     */
    public function unhideAccount(string $vaultAccountId): void
    {
        $this->client->post("/v1/vault/accounts/{$vaultAccountId}/unhide");
    }

    /**
     * Get vault account asset.
     */
    public function getAsset(string $vaultAccountId, string $assetId): VaultAsset
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}");
        
        return new VaultAsset($response);
    }
     /**
     * Get raw asset address response for a vault account asset.
     */
    public function getAssetAddress(string $vaultAccountId, string $assetId): array
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/addresses");
        return $response;
    }
    
    /**
     * Create a new vault asset.
     */
    public function createAsset(string $vaultAccountId, string $assetId, ?string $eosAccountName = null): VaultAsset
    {
        $data = [];
        if ($eosAccountName !== null) {
            $data['eosAccountName'] = $eosAccountName;
        }
        
        $response = $this->client->post("/v1/vault/accounts/{$vaultAccountId}/{$assetId}", $data);
        
        return new VaultAsset($response);
    }

    /**
     * List deposit addresses for a vault account asset.
     *
     * @return array<DepositAddress>
     */
    public function listDepositAddresses(string $vaultAccountId, string $assetId): array
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/addresses");
        
        return array_map(fn ($item) => new DepositAddress($item), $response['addresses'] ?? []);
    }

   

    /**
     * Create a new deposit address.
     */
    public function createDepositAddress(string $vaultAccountId, string $assetId, ?string $description = null, ?string $customerRefId = null): DepositAddress
    {
        $data = [];
        if ($description !== null) {
            $data['description'] = $description;
        }
        if ($customerRefId !== null) {
            $data['customerRefId'] = $customerRefId;
        }
        
        $response = $this->client->post("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/addresses", $data);
        
        return new DepositAddress($response);
    }

    /**
     * Get the maximum spendable amount for a vault account asset.
     */
    public function getMaxSpendableAmount(string $vaultAccountId, string $assetId): string
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/max_spendable_amount");
        
        return $response['maxSpendableAmount'] ?? '0';
    }

    /**
     * Get the maximum BIP44 index used for a vault account asset.
     */
    public function getMaxBip44IndexUsed(string $vaultAccountId, string $assetId): int
    {
        $response = $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/max_bip44_index_used");
        
        return $response['maxBip44IndexUsed'] ?? 0;
    }

    /**
     * List vault account asset addresses with pagination.
     */
    public function listAddresses(string $vaultAccountId, string $assetId, ?int $limit = null, ?int $before = null, ?int $after = null): array
    {
        $params = [];
        if ($limit !== null) {
            $params['limit'] = $limit;
        }
        if ($before !== null) {
            $params['before'] = $before;
        }
        if ($after !== null) {
            $params['after'] = $after;
        }
        
        return $this->client->get("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/addresses_paginated", $params);
    }

    /**
     * Get asset balance for multiple vault accounts.
     */
    public function getAssetBalance(string $assetId): array
    {
        return $this->client->get("/v1/vault/assets/{$assetId}");
    }

    /**
     * Refresh asset balance.
     */
    public function refreshAssetBalance(string $vaultAccountId, string $assetId): VaultAsset
    {
        $response = $this->client->post("/v1/vault/accounts/{$vaultAccountId}/{$assetId}/balance");
        
        return new VaultAsset($response);
    }
}
