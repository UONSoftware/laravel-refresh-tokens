<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Throwable;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenExpired;
use UonSoftware\RefreshTokens\Exceptions\InvalidRefreshToken;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenNotFound;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface RefreshTokenVerifier
 *
 * @package UonSoftware\RefreshTokens\Contracts
 */
interface RefreshTokenVerifier
{

    /**
     * @param string|null $token
     *
     * @return bool|\UonSoftware\RefreshTokens\RefreshToken
     * @throws RefreshTokenExpired
     * @throws ModelNotFoundException
     * @throws RefreshTokenNotFound
     * @throws InvalidRefreshToken
     * @throws Throwable
     */
    public function verify(?string $token);
}
