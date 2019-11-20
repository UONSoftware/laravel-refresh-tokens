<?php


namespace UonSoftware\RefreshTokens\Contracts;


interface UserJwtExpired
{
    public function hasTokenExpired(): bool;
}
