<?php


namespace UonSoftware\RefreshTokens\Contracts;


interface Authenticate
{
    public function authenticate(string $jwt);
}
