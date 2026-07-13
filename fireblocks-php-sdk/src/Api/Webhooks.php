<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class Webhooks
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function list(): array { return $this->client->get('/v1/webhooks'); }
    public function get(string $webhookId): array { return $this->client->get("/v1/webhooks/{$webhookId}"); }
    public function create(string $url, array $events): array { return $this->client->post('/v1/webhooks', ['url' => $url, 'events' => $events]); }
    public function update(string $webhookId, array $data): array { return $this->client->patch("/v1/webhooks/{$webhookId}", $data); }
    public function delete(string $webhookId): void { $this->client->delete("/v1/webhooks/{$webhookId}"); }
    public function resend(string $webhookId, string $eventId): void { $this->client->post("/v1/webhooks/{$webhookId}/resend/{$eventId}"); }
    public function getPublicKey(): array { return $this->client->get('/v1/webhooks/public_key'); }
}
