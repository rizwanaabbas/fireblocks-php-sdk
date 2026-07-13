<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class DepositAddress
{
    public string $assetId;
    public string $address;
    public ?string $description = null;
    public ?string $tag = null;
    public ?string $type = null;
    public ?string $customerRefId = null;
    public ?string $addressFormat = null;
    public ?string $legacyAddress = null;
    public ?string $enterpriseAddress = null;

    public function __construct(array $data = [])
    {
        $this->assetId = $data['assetId'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->tag = $data['tag'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->customerRefId = $data['customerRefId'] ?? null;
        $this->addressFormat = $data['addressFormat'] ?? null;
        $this->legacyAddress = $data['legacyAddress'] ?? null;
        $this->enterpriseAddress = $data['enterpriseAddress'] ?? null;
    }
}
