<?php


namespace UonSoftware\RefreshTokens\Service;


use InvalidArgumentException;
use UonSoftware\RefreshTokens\RefreshToken;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenEncoder as Encoder;

/**
 * Class RefreshTokenEncoder
 *
 * @package UonSoftware\RefreshTokens\Service
 */
class RefreshTokenEncoder implements Encoder
{

    /**
     * @param string|RefreshToken $data
     * @param string              $signature
     *
     * @return string
     */
    public function encode($data, string $signature): string
    {
        if ($data instanceof RefreshToken) {
            $data = $this->encodeData($data);
        }

        if (!is_string($data)) {
            throw new InvalidArgumentException('Data is not string');
        }
        return $data . '.' . $signature;
    }

    public function encodeData(RefreshToken $refreshToken): string
    {
        $timestamp = $refreshToken->expires->getTimestamp();
        return sprintf(static::DATA_FORMAT, $refreshToken->token, $timestamp);
    }
}
