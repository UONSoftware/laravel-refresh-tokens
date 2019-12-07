<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UonSoftware\RefreshTokens\Exceptions\InvalidRefreshToken;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenExpired;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenNotFound;

/**
 * Interface RefreshTokenVerifier
 *
 * @package UonSoftware\RefreshTokens\Contracts
 */
interface RefreshTokenVerifier
{

    /**
     * @throws RefreshTokenExpired
     * @throws ModelNotFoundException
     * @throws RefreshTokenNotFound
     * @throws InvalidRefreshToken
     * @throws Throwable
     *
     * @param string|null $token
     *
     * @return bool|\UonSoftware\RefreshTokens\RefreshToken
     */
    public function verify(?string $token);
}
