<?php


namespace UonSoftware\RefreshTokens\Contracts;


use UonSoftware\RefreshTokens\RefreshToken;

interface RefreshTokenEncoder extends RefreshTokenParser
{
    /**
     * @param RefreshToken|string $data
     * @param string $signature
     * @return string
     */
    public function encode($data, string $signature): string;

    public function encodeData(RefreshToken $refreshToken): string;
}
