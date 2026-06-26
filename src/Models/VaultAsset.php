<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Models;

class VaultAsset
{
    public string $id;
    public string $total;
    public string $available;
    public ?string $pending = null;
    public ?string $frozen = null;
    public ?string $lockedAmount = null;
    public ?string $staked = null;
    public ?string $totalStakedCPU = null;
    public ?string $totalStakedNetwork = null;
    public ?string $selfStakedCPU = null;
    public ?string $selfStakedNetwork = null;
    public ?string $pendingRefundCpu = null;
    public ?string $pendingRefundNetwork = null;
    public ?string $blockHash = null;
    public ?string $blockHeight = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? '';
        $this->total = $data['total'] ?? '0';
        $this->available = $data['available'] ?? '0';
        $this->pending = $data['pending'] ?? null;
        $this->frozen = $data['frozen'] ?? null;
        $this->lockedAmount = $data['lockedAmount'] ?? null;
        $this->staked = $data['staked'] ?? null;
        $this->totalStakedCPU = $data['totalStakedCPU'] ?? null;
        $this->totalStakedNetwork = $data['totalStakedNetwork'] ?? null;
        $this->selfStakedCPU = $data['selfStakedCPU'] ?? null;
        $this->selfStakedNetwork = $data['selfStakedNetwork'] ?? null;
        $this->pendingRefundCpu = $data['pendingRefundCpu'] ?? null;
        $this->pendingRefundNetwork = $data['pendingRefundNetwork'] ?? null;
        $this->blockHash = $data['blockHash'] ?? null;
        $this->blockHeight = $data['blockHeight'] ?? null;
    }
}
