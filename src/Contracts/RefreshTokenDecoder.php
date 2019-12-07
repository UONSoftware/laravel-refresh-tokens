<?php


namespace UonSoftware\RefreshTokens\Contracts;


interface RefreshTokenDecoder extends RefreshTokenParser
{
    public function decode(string $refreshToken, bool $selectFromDatabase = true): array;
}
