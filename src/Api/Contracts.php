<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class Contracts
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function callReadFunction(string $vaultAccountId, string $assetId, string $contractAddress, string $functionSignature, ?array $functionArguments = null): array {
        $data = ['vaultAccountId' => $vaultAccountId, 'assetId' => $assetId, 'contractAddress' => $contractAddress, 'functionSignature' => $functionSignature];
        if ($functionArguments) $data['functionArguments'] = $functionArguments;
        return $this->client->post('/v1/contracts/call', $data);
    }
    public function callWriteFunction(string $vaultAccountId, string $assetId, string $contractAddress, string $functionSignature, ?array $functionArguments = null, ?string $amount = null): array {
        $data = ['vaultAccountId' => $vaultAccountId, 'assetId' => $assetId, 'contractAddress' => $contractAddress, 'functionSignature' => $functionSignature];
        if ($functionArguments) $data['functionArguments'] = $functionArguments;
        if ($amount) $data['amount'] = $amount;
        return $this->client->post('/v1/contracts/call_write', $data);
    }
}
