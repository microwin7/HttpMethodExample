<?php

namespace Gravita\Http\Exceptions;

class HttpErrorException extends \Exception
{
    public function __construct($message)
    {
        $code = $this->messageToCode($message);
        parent::__construct($message, $code);
    }
    private function messageToCode($message)
    {
        return match ($message) {
            AUTH_TOKEN_EXPIRED => 1001,
            AUTH_INVALID_TOKEN => 1002,
            default => 0
        };
    }
}
