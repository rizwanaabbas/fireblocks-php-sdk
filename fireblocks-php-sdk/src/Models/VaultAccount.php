<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class VaultAccount
{
    public string $id;
    public string $name;
    public ?string $type = null;
    public ?string $hiddenOnUi = null;
    public array $assets = [];
    public ?string $customerRefId = null;
    public ?bool $autoFuel = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->type = $data['type'] ?? null;
        $this->hiddenOnUi = $data['hiddenOnUi'] ?? null;
        $this->assets = $data['assets'] ?? [];
        $this->customerRefId = $data['customerRefId'] ?? null;
        $this->autoFuel = $data['autoFuel'] ?? null;
    }
}
