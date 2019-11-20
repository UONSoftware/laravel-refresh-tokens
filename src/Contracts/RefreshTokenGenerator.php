<?php


namespace UonSoftware\RefreshTokens\Contracts;


use UonSoftware\RefreshTokens\RefreshToken;
use UonSoftware\RsaSigner\Exceptions\SignatureCorrupted;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

interface RefreshTokenGenerator
{
    /**
     * @param RefreshToken|string|null $refreshToken
     * @param integer|string|null $userId
     * @param int|null $length
     *
     * @return array
     * @throws SignatureCorrupted
     * @throws ModelNotFoundException
     * @throws Throwable
     */
    public function generateNewRefreshToken(RefreshToken $refreshToken, $userId = null, ?int $length = null): array;
}
