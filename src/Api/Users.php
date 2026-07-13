<?php
declare(strict_types=1);

namespace Fireblocks\Sdk\Api;

class Users
{
    private FireblocksClient $client;
    public function __construct(FireblocksClient $client) { $this->client = $client; }
    
    public function list(): array { return $this->client->get('/v1/users'); }
    public function get(string $userId): array { return $this->client->get("/v1/users/{$userId}"); }
    public function getByRole(string $role): array { return $this->client->get('/v1/users', ['role' => $role]); }
}
