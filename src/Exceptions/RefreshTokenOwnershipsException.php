<?php


namespace UonSoftware\RefreshTokens\Exceptions;


use Exception;
use Throwable;

class RefreshTokenOwnershipsException extends Exception
{
    public function __construct($message = 'Refresh token doesn\'t belong to you', $code = 0, Throwable $previous =
    null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}