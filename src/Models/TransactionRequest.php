<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

use Fireblocks\Sdk\Traits\Arrayable;

class TransactionRequest
{
    use Arrayable;

    public ?string $assetId = null;
    public ?string $sourceType = null;
    public ?string $sourceId = null;
    public ?string $sourceWalletId = null;
    public ?string $destinationType = null;
    public ?string $destinationId = null;
    public ?string $destinationAddress = null;
    public ?string $destinationWalletId = null;
    public ?string $destinationTag = null;
    public ?string $amount = null;
    public ?bool $treatAsGrossAmount = null;
    public ?string $feeLevel = null;
    public ?string $requestedGasPrice = null;
    public ?string $requestedGasLimit = null;
    public ?string $priorityFee = null;
    public ?string $maxFee = null;
    public ?string $networkFee = null;
    public ?string $failOnLowFee = null;
    public ?string $customerRefId = null;
    public ?string $externalTxId = null;
    public ?string $note = null;
    public ?bool $replaceTxByHash = null;
    public ?array $extraParameters = null;
    public ?bool $forceSweep = null;
    public ?array $destinations = null;
    public ?array $amountInfo = null;

    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Set source as vault account.
     */
    public function fromVaultAccount(string $vaultAccountId, ?string $walletId = null): self
    {
        $this->sourceType = 'VAULT_ACCOUNT';
        $this->sourceId = $vaultAccountId;
        $this->sourceWalletId = $walletId;
        return $this;
    }

    /**
     * Set source as exchange account.
     */
    public function fromExchange(string $exchangeAccountId): self
    {
        $this->sourceType = 'EXCHANGE_ACCOUNT';
        $this->sourceId = $exchangeAccountId;
        return $this;
    }

    /**
     * Set source as fiat account.
     */
    public function fromFiat(string $fiatAccountId): self
    {
        $this->sourceType = 'FIAT_ACCOUNT';
        $this->sourceId = $fiatAccountId;
        return $this;
    }

    /**
     * Set destination as vault account.
     */
    public function toVaultAccount(string $vaultAccountId, ?string $walletId = null): self
    {
        $this->destinationType = 'VAULT_ACCOUNT';
        $this->destinationId = $vaultAccountId;
        $this->destinationWalletId = $walletId;
        return $this;
    }

    /**
     * Set destination as one-time address.
     */
    public function toOneTimeAddress(string $address, ?string $tag = null): self
    {
        $this->destinationType = 'ONE_TIME_ADDRESS';
        $this->destinationAddress = $address;
        $this->destinationTag = $tag;
        return $this;
    }

    /**
     * Set destination as exchange account.
     */
    public function toExchange(string $exchangeAccountId): self
    {
        $this->destinationType = 'EXCHANGE_ACCOUNT';
        $this->destinationId = $exchangeAccountId;
        return $this;
    }

    /**
     * Set destination as fiat account.
     */
    public function toFiat(string $fiatAccountId): self
    {
        $this->destinationType = 'FIAT_ACCOUNT';
        $this->destinationId = $fiatAccountId;
        return $this;
    }

    /**
     * Set destination as network connection.
     */
    public function toNetworkConnection(string $connectionId, ?string $walletId = null): self
    {
        $this->destinationType = 'NETWORK_CONNECTION';
        $this->destinationId = $connectionId;
        $this->destinationWalletId = $walletId;
        return $this;
    }

    /**
     * Set destination as compound.
     */
    public function toCompound(string $compoundId): self
    {
        $this->destinationType = 'COMPOUND';
        $this->destinationId = $compoundId;
        return $this;
    }

    /**
     * Set destination as internal wallet.
     */
    public function toInternalWallet(string $walletId): self
    {
        $this->destinationType = 'INTERNAL_WALLET';
        $this->destinationId = $walletId;
        return $this;
    }

    /**
     * Set destination as external wallet.
     */
    public function toExternalWallet(string $walletId): self
    {
        $this->destinationType = 'EXTERNAL_WALLET';
        $this->destinationId = $walletId;
        return $this;
    }

    /**
     * Set destination as contract wallet.
     */
    public function toContractWallet(string $walletId): self
    {
        $this->destinationType = 'CONTRACT_WALLET';
        $this->destinationId = $walletId;
        return $this;
    }

    /**
     * Set destination as unknown peer (for receiving).
     */
    public function fromUnknownPeer(): self
    {
        $this->sourceType = 'UNKNOWN';
        return $this;
    }

    /**
     * Set the asset ID.
     */
    public function withAsset(string $assetId): self
    {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * Set the amount.
     */
    public function withAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Set fee level (LOW, MEDIUM, HIGH).
     */
    public function withFeeLevel(string $feeLevel): self
    {
        $this->feeLevel = $feeLevel;
        return $this;
    }

    /**
     * Set note.
     */
    public function withNote(string $note): self
    {
        $this->note = $note;
        return $this;
    }

    /**
     * Set external transaction ID.
     */
    public function withExternalTxId(string $externalTxId): self
    {
        $this->externalTxId = $externalTxId;
        return $this;
    }

    /**
     * Set customer reference ID.
     */
    public function withCustomerRefId(string $customerRefId): self
    {
        $this->customerRefId = $customerRefId;
        return $this;
    }
}
