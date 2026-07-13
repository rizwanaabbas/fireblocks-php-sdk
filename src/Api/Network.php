<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class Network
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function getConnections(): array { return $this->client->get('/v1/network_connections'); }
    public function getConnection(string $connectionId): array { return $this->client->get("/v1/network_connections/{$connectionId}"); }
    public function createConnection(array $data): array { return $this->client->post('/v1/network_connections', $data); }
    public function deleteConnection(string $connectionId): void { $this->client->delete("/v1/network_connections/{$connectionId}"); }
    public function getNetworkId(): array { return $this->client->get('/v1/network_id'); }
    public function getSupportedAssets(): array { return $this->client->get('/v1/supported_assets'); }
}
