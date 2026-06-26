<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Exceptions;

class RateLimitException extends FireblocksException
{
    protected ?int $retryAfter;

    public function __construct(string $message = 'Rate limit exceeded', int $code = 429, ?int $retryAfter = null)
    {
        parent::__construct($message, $code);
        $this->retryAfter = $retryAfter;
    }

    /**
     * Get retry after seconds.
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
