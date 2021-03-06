<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenOwnershipsException;

interface RefreshTokenRepository
{
    public function getTokens(int $page, int $perPage): LengthAwarePaginator;

    public function getTokensForUser(int $page = 1, int $perPage = 10, ?int $userId = null): LengthAwarePaginator;


    /**
     * @throws \Throwable
     * @throws \UonSoftware\RefreshTokens\Exceptions\InvalidRefreshToken
     * @throws \UonSoftware\RefreshTokens\Exceptions\RefreshTokenExpired
     * @throws \UonSoftware\RefreshTokens\Exceptions\RefreshTokenNotFound
     * @throws RefreshTokenOwnershipsException
     *
     * @param string $token
     *
     * @param int    $user
     *
     * @return bool
     */
    public function revokeToken(string $token, int $user): bool;
}