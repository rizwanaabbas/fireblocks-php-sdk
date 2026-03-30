<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

use Fireblocks\Sdk\Models\Transaction;
use Fireblocks\Sdk\Models\TransactionRequest;
use Fireblocks\Sdk\Models\TransferPeerPath;

class Transactions
{
    private FireblocksClient $client;

    public function __construct(FireblocksClient $client)
    {
        $this->client = $client;
    }

    /**
     * List transactions with filters.
     */
    public function list(array $params = []): array
    {
        return $this->client->get('/v1/transactions', $params);
    }

    /**
     * Get a specific transaction.
     */
    public function get(string $txId): Transaction
    {
        $response = $this->client->get("/v1/transactions/{$txId}");
        
        return new Transaction($response);
    }

    /**
     * Create a new transaction.
     */
    public function create(TransactionRequest $request): Transaction
    {
        $response = $this->client->post('/v1/transactions', $request->toArray());
        
        return new Transaction($response);
    }

    /**
     * Validate a destination address.
     */
    public function validateAddress(string $assetId, string $address): array
    {
        return $this->client->get("/v1/transactions/validate_address/{$assetId}/{$address}");
    }

    /**
     * Get transaction by external ID.
     */
    public function getByExternalId(string $externalTxId): Transaction
    {
        $response = $this->client->get('/v1/transactions/external_tx_id/' . $externalTxId);
        
        return new Transaction($response);
    }

    /**
     * Cancel a transaction.
     */
    public function cancel(string $txId, ?string $reason = null): Transaction
    {
        $data = [];
        if ($reason !== null) {
            $data['reason'] = $reason;
        }
        
        $response = $this->client->post("/v1/transactions/{$txId}/cancel", $data);
        
        return new Transaction($response);
    }

    /**
     * Drop/replace a transaction.
     */
    public function dropReplace(string $txId, string $feeLevel = 'MEDIUM', ?string $requestedGasPrice = null): Transaction
    {
        $data = ['feeLevel' => $feeLevel];
        if ($requestedGasPrice !== null) {
            $data['requestedGasPrice'] = $requestedGasPrice;
        }
        
        $response = $this->client->post("/v1/transactions/{$txId}/drop", $data);
        
        return new Transaction($response);
    }

    /**
     * Freeze a transaction.
     */
    public function freeze(string $txId): void
    {
        $this->client->post("/v1/transactions/{$txId}/freeze");
    }

    /**
     * Unfreeze a transaction.
     */
    public function unfreeze(string $txId): void
    {
        $this->client->post("/v1/transactions/{$txId}/unfreeze");
    }

    /**
     * Estimate transaction fee.
     */
    public function estimateFee(TransactionRequest $request): array
    {
        return $this->client->post('/v1/transactions/estimate_fee', $request->toArray());
    }

    /**
     * Set confirmation threshold for a transaction.
     */
    public function setConfirmationThreshold(string $txId, int $requiredConfirmationsNumber): void
    {
        $this->client->post("/v1/transactions/{$txId}/set_confirmation_threshold", [
            'requiredConfirmationsNumber' => $requiredConfirmationsNumber,
        ]);
    }
}
