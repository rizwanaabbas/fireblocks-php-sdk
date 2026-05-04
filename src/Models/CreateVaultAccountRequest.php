<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class CreateVaultAccountRequest
{
    public string $name;
    public ?bool $hiddenOnUI = null;
    public ?string $customerRefId = null;
    public ?bool $autoFuel = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function withHiddenOnUI(bool $hidden): self
    {
        $this->hiddenOnUI = $hidden;
        return $this;
    }

    public function withCustomerRefId(string $refId): self
    {
        $this->customerRefId = $refId;
        return $this;
    }

    public function withAutoFuel(bool $autoFuel): self
    {
        $this->autoFuel = $autoFuel;
        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if ($this->hiddenOnUI !== null) {
            $data['hiddenOnUI'] = $this->hiddenOnUI;
        }

        if ($this->customerRefId !== null) {
            $data['customerRefId'] = $this->customerRefId;
        }

        if ($this->autoFuel !== null) {
            $data['autoFuel'] = $this->autoFuel;
        }

        return $data;
    }
}
