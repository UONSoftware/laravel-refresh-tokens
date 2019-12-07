<?php


namespace UonSoftware\RefreshTokens\Contracts;


interface UserJwtExpired
{
    /**
     * Checks if JWT has expired
     *
     * @return bool
     */
    public function hasTokenExpired(): bool;
}
