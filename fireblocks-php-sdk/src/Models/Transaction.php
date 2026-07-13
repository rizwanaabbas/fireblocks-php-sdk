<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class Transaction
{
    public string $id;
    public string $assetId;
    public ?string $source = null;
    public ?string $destination = null;
    public ?string $requestedAmount = null;
    public ?string $amount = null;
    public ?string $amountInfo = null;
    public ?string $fee = null;
    public ?string $feeCurrency = null;
    public ?string $networkFee = null;
    public ?string $netAmount = null;
    public ?string $status = null;
    public ?string $subStatus = null;
    public ?string $txHash = null;
    public ?int $numOfConfirmations = null;
    public ?string $createdAt = null;
    public ?string $lastUpdated = null;
    public ?string $completedAt = null;
    public ?string $destinationAddress = null;
    public ?string $destinationAddressDescription = null;
    public ?string $destinationTag = null;
    public ?string $sourceAddress = null;
    public ?string $destinationNetworkId = null;
    public ?array $signedMessages = null;
    public ?array $extraParameters = null;
    public ?string $externalTxId = null;
    public ?string $operation = null;
    public ?array $feePayerInfo = null;
    public ?string $note = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? '';
        $this->assetId = $data['assetId'] ?? '';
        $this->source = $data['source'] ?? null;
        $this->destination = $data['destination'] ?? null;
        $this->requestedAmount = $data['requestedAmount'] ?? null;
        $this->amount = $data['amount'] ?? null;
        $this->amountInfo = $data['amountInfo'] ?? null;
        $this->fee = $data['fee'] ?? null;
        $this->feeCurrency = $data['feeCurrency'] ?? null;
        $this->networkFee = $data['networkFee'] ?? null;
        $this->netAmount = $data['netAmount'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->subStatus = $data['subStatus'] ?? null;
        $this->txHash = $data['txHash'] ?? null;
        $this->numOfConfirmations = $data['numOfConfirmations'] ?? null;
        $this->createdAt = $data['createdAt'] ?? null;
        $this->lastUpdated = $data['lastUpdated'] ?? null;
        $this->completedAt = $data['completedAt'] ?? null;
        $this->destinationAddress = $data['destinationAddress'] ?? null;
        $this->destinationAddressDescription = $data['destinationAddressDescription'] ?? null;
        $this->destinationTag = $data['destinationTag'] ?? null;
        $this->sourceAddress = $data['sourceAddress'] ?? null;
        $this->destinationNetworkId = $data['destinationNetworkId'] ?? null;
        $this->signedMessages = $data['signedMessages'] ?? null;
        $this->extraParameters = $data['extraParameters'] ?? null;
        $this->externalTxId = $data['externalTxId'] ?? null;
        $this->operation = $data['operation'] ?? null;
        $this->feePayerInfo = $data['feePayerInfo'] ?? null;
        $this->note = $data['note'] ?? null;
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['PENDING', 'PENDING_SIGNATURE', 'BROADCASTING', 'CONFIRMING'], true);
    }

    /**
     * Check if transaction failed.
     */
    public function isFailed(): bool
    {
        return in_array($this->status, ['FAILED', 'REJECTED', 'CANCELLED'], true);
    }
}
