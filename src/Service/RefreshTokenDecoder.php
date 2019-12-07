<?php


namespace UonSoftware\RefreshTokens\Service;


use UonSoftware\RefreshTokens\RefreshToken;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;

/**
 * Class RefreshTokenDecoder
 *
 * @package UonSoftware\RefreshTokens\Service
 */
class RefreshTokenDecoder implements Decoder
{

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @param boolean $selectFromDatabase
     * @param string  $refreshToken
     *
     * @return array
     */
    public function decode(string $refreshToken, bool $selectFromDatabase = true): array
    {
        [$token, $timeStamp, $signature] = explode('.', $refreshToken);
        $rf = null;
        if ($selectFromDatabase) {
            /** @var RefreshToken $rf */
            $rf = RefreshToken::query()->where('token', '=', $token)->firstOrFail();
        }
        return [
            $rf,
            sprintf(static::DATA_FORMAT, $token, $timeStamp),
            $token,
            $signature,
        ];
    }
}
