<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Exceptions;

class NotFoundException extends FireblocksException
{
    public function __construct(string $message = 'Resource not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
