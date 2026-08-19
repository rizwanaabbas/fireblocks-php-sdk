<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class Transaction
{
    public string $id;
    public string $assetId;
    /** @var array<string, mixed>|null Transfer peer path response from Fireblocks API */
    public ?array $source = null;
    /** @var array<string, mixed>|null Transfer peer path response from Fireblocks API */
    public ?array $destination = null;
    public ?string $requestedAmount = null;
    public ?string $amount = null;
    /** @var array<string, mixed>|null */
    public ?array $amountInfo = null;
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
        $this->source = $this->normalizePeerPath($data['source'] ?? null);
        $this->destination = $this->normalizePeerPath($data['destination'] ?? null);
        $this->requestedAmount = $this->stringOrNull($data['requestedAmount'] ?? null);
        $this->amount = $this->stringOrNull($data['amount'] ?? null);
        $this->amountInfo = is_array($data['amountInfo'] ?? null) ? $data['amountInfo'] : null;
        $this->fee = $this->stringOrNull($data['fee'] ?? null);
        $this->feeCurrency = $this->stringOrNull($data['feeCurrency'] ?? null);
        $this->networkFee = $this->stringOrNull($data['networkFee'] ?? null);
        $this->netAmount = $this->stringOrNull($data['netAmount'] ?? null);
        $this->status = $this->stringOrNull($data['status'] ?? null);
        $this->subStatus = $this->stringOrNull($data['subStatus'] ?? null);
        $this->txHash = $this->stringOrNull($data['txHash'] ?? null);
        $this->numOfConfirmations = isset($data['numOfConfirmations']) ? (int) $data['numOfConfirmations'] : null;
        $this->createdAt = $this->stringOrNull($data['createdAt'] ?? null);
        $this->lastUpdated = $this->stringOrNull($data['lastUpdated'] ?? null);
        $this->completedAt = $this->stringOrNull($data['completedAt'] ?? null);
        $this->destinationAddress = $this->stringOrNull($data['destinationAddress'] ?? null);
        $this->destinationAddressDescription = $this->stringOrNull($data['destinationAddressDescription'] ?? null);
        $this->destinationTag = $this->stringOrNull($data['destinationTag'] ?? null);
        $this->sourceAddress = $this->stringOrNull($data['sourceAddress'] ?? null);
        $this->destinationNetworkId = $this->stringOrNull($data['destinationNetworkId'] ?? null);
        $this->signedMessages = is_array($data['signedMessages'] ?? null) ? $data['signedMessages'] : null;
        $this->extraParameters = is_array($data['extraParameters'] ?? null) ? $data['extraParameters'] : null;
        $this->externalTxId = $this->stringOrNull($data['externalTxId'] ?? null);
        $this->operation = $this->stringOrNull($data['operation'] ?? null);
        $this->feePayerInfo = is_array($data['feePayerInfo'] ?? null) ? $data['feePayerInfo'] : null;
        $this->note = $this->stringOrNull($data['note'] ?? null);
    }

    /**
     * @param mixed $value
     * @return array<string, mixed>|null
     */
    private function normalizePeerPath($value): ?array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            return ['id' => $value];
        }

        return null;
    }

    /**
     * @param mixed $value
     */
    private function stringOrNull($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return null;
    }

    public function sourceId(): ?string
    {
        return isset($this->source['id']) ? (string) $this->source['id'] : null;
    }

    public function destinationId(): ?string
    {
        return isset($this->destination['id']) ? (string) $this->destination['id'] : null;
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
