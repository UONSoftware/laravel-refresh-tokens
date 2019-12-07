<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Throwable;
use UonSoftware\RefreshTokens\RefreshToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UonSoftware\RsaSigner\Exceptions\SignatureCorrupted;

interface RefreshTokenGenerator
{
    /**
     * @throws SignatureCorrupted
     * @throws ModelNotFoundException
     * @throws Throwable
     *
     * @param RefreshToken|string|null $refreshToken
     * @param integer|string|null      $userId
     * @param int|null                 $length
     *
     * @return array
     */
    public function generateNewRefreshToken(RefreshToken $refreshToken, $userId = null, ?int $length = null): array;
}
