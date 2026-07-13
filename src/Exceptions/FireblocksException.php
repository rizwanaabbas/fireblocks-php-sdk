<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Exceptions;

use Exception;

class FireblocksException extends Exception
{
    protected ?string $errorCode;
    protected ?array $errorData;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?string $errorCode = null,
        ?array $errorData = null,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->errorData = $errorData;
    }

    /**
     * Get the Fireblocks error code.
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * Get additional error data.
     */
    public function getErrorData(): ?array
    {
        return $this->errorData;
    }
}
