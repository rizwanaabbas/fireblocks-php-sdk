<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Exceptions;

class AuthenticationException extends FireblocksException
{
    public function __construct(string $message = 'Authentication failed', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
