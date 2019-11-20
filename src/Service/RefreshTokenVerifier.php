<?php


namespace UonSoftware\RefreshTokens\Service;


use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use Throwable;
use Hob\RsaSigner\Contracts\RsaSigner;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenExpired;
use UonSoftware\RefreshTokens\Exceptions\InvalidRefreshToken;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenNotFound;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier as Contract;

/**
 * Class RefreshTokenVerifier
 * @package UonSoftware\RefreshTokens\Service
 */
class RefreshTokenVerifier implements Contract
{
    /**
     * @var RsaSigner
     */
    protected $rsaSigner;

    /**
     * @var Decoder
     */
    protected $decoder;

    /**
     * RefreshTokenVerifier constructor.
     * @param RsaSigner $rsaSigner
     * @param Decoder $decoder
     */
    public function __construct(RsaSigner $rsaSigner, Decoder $decoder)
    {
        $this->rsaSigner = $rsaSigner;
        $this->decoder = $decoder;
    }

    /**
     * @param  string|null  $token
     *
     * @return bool|\UonSoftware\RefreshTokens\RefreshToken
     * @throws RefreshTokenExpired
     * @throws ModelNotFoundException
     * @throws RefreshTokenNotFound
     * @throws InvalidRefreshToken
     * @throws Throwable
     */
    public function verify(?string $token)
    {
        if ($token === null) {
            throw new RefreshTokenNotFound();
        }

        [$refreshToken, $data, $signature] = $this->decoder->decode($token);

        if (($data = $this->rsaSigner->verify($data, $signature)) === null) {
            throw new InvalidRefreshToken();
        }

        if (now()->isAfter($refreshToken->expires)) {
            throw new RefreshTokenExpired();
        }

        return $refreshToken;
    }
}
