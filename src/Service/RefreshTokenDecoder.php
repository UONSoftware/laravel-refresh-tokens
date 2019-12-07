<?php


namespace UonSoftware\RefreshTokens\Service;


use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use UonSoftware\RefreshTokens\RefreshToken;

/**
 * Class RefreshTokenDecoder
 * @package UonSoftware\RefreshTokens\Service
 */
class RefreshTokenDecoder implements Decoder
{

    /**
     * @param string $refreshToken
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return array
     */
    public function decode(string $refreshToken): array
    {
        [$token, $timeStamp, $signature] = explode('.', $refreshToken);

        /** @var RefreshToken $rf */
        $rf = RefreshToken::query()->where('token', '=', $token)->firstOrFail();

        return [$rf, sprintf(static::DATA_FORMAT, $token, $timeStamp), $signature];
    }
}
