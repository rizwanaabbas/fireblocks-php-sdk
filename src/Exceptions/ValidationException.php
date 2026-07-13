<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Exceptions;

class ValidationException extends FireblocksException
{
    protected array $errors = [];

    public function __construct(string $message = 'Validation failed', array $errors = [], int $code = 422)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    /**
     * Get validation errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
