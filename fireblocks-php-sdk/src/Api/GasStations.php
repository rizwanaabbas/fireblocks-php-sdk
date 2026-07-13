<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class GasStations
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function getConfiguration(): array { return $this->client->get('/v1/gas_station/configuration'); }
    public function setConfiguration(array $data): array { return $this->client->put('/v1/gas_station/configuration', $data); }
    public function getConfigurationByAsset(string $assetId): array { return $this->client->get("/v1/gas_station/configuration/{$assetId}"); }
}
