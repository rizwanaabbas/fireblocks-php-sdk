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
        $response = $this->client->post('/v1/transactions', $this->buildPayload($request));

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
        return $this->client->post('/v1/transactions/estimate_fee', $this->buildPayload($request));
    }

    /**
     * Build the correct Fireblocks API payload from a TransactionRequest.
     */
    private function buildPayload(TransactionRequest $request): array
    {
        $payload = [
            'assetId' => $request->assetId,
            'source' => [
                'type' => $request->sourceType,
                'id' => $request->sourceId,
            ],
            'destination' => [
                'type' => $request->destinationType,
            ],
            'amount' => $request->amount,
        ];

        if ($request->sourceWalletId !== null) {
            $payload['source']['walletId'] = $request->sourceWalletId;
        }

        if ($request->destinationType === 'ONE_TIME_ADDRESS') {
            $payload['destination']['oneTimeAddress'] = [
                'address' => $request->destinationAddress,
            ];
            if ($request->destinationTag !== null) {
                $payload['destination']['oneTimeAddress']['tag'] = $request->destinationTag;
            }
        } else {
            $payload['destination']['id'] = $request->destinationId;
            if ($request->destinationWalletId !== null) {
                $payload['destination']['walletId'] = $request->destinationWalletId;
            }
        }

        if ($request->feeLevel !== null) {
            $payload['feeLevel'] = $request->feeLevel;
        }
        if ($request->note !== null) {
            $payload['note'] = $request->note;
        }
        if ($request->externalTxId !== null) {
            $payload['externalTxId'] = $request->externalTxId;
        }
        if ($request->customerRefId !== null) {
            $payload['customerRefId'] = $request->customerRefId;
        }
        if ($request->treatAsGrossAmount !== null) {
            $payload['treatAsGrossAmount'] = $request->treatAsGrossAmount;
        }
        if ($request->failOnLowFee !== null) {
            $payload['failOnLowFee'] = $request->failOnLowFee;
        }
        if ($request->forceSweep !== null) {
            $payload['forceSweep'] = $request->forceSweep;
        }
        if ($request->replaceTxByHash !== null) {
            $payload['replaceTxByHash'] = $request->replaceTxByHash;
        }
        if ($request->extraParameters !== null) {
            $payload['extraParameters'] = $request->extraParameters;
        }

        return $payload;
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
