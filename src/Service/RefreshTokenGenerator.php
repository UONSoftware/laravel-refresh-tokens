<?php


namespace UonSoftware\RefreshTokens\Service;


use InvalidArgumentException;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenEncoder as Encoder;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier as Verifier;
use UonSoftware\RsaSigner\Contracts\RsaSigner as Signer;
use UonSoftware\RsaSigner\Exceptions\SignatureCorrupted;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use UonSoftware\RefreshTokens\RefreshToken;
use Illuminate\Contracts\Config\Repository as Config;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenGenerator as Contract;
use Throwable;
use TypeError;

/**
 * Class RefreshTokenGenerator
 *
 * @package UonSoftware\RefreshTokens\Service
 */
class RefreshTokenGenerator implements Contract
{
    /**
     * @var Encoder
     */
    protected $encoder;

    /**
     * @var Decoder
     */
    protected $decoder;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Verifier
     */
    protected $verifier;

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * RefreshTokenGenerator constructor.
     *
     * @param Encoder $encoder
     * @param Decoder $decoder
     * @param Signer $signer
     * @param Verifier $verifier
     * @param Config $config
     */
    public function __construct(Encoder $encoder, Decoder $decoder, Signer $signer, Verifier $verifier, Config $config)
    {
        $this->encoder = $encoder;
        $this->config = $config;
        $this->decoder = $decoder;
        $this->verifier = $verifier;
        $this->signer = $signer;
    }

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
    public function generateNewRefreshToken($refreshToken, $userId = null, ?int $length = null): array
    {

        if ($length === null) {
            $length = $this->config->get('refresh_tokens.token_length');
        }

        if (is_string($refreshToken)) {
            [$refreshToken] = $this->decoder->decode($refreshToken);

        } else if ($refreshToken === null) {
            $refreshToken = new RefreshToken();
        }


        if (!$refreshToken instanceof RefreshToken) {
            throw new TypeError('Refresh token is invalid');
        }


        $data = Str::random($length);
        $refreshToken->token = $data;
        $refreshToken->expires = now()->addMinutes($this->config->get('jwt.refresh_ttl'));


        if ($refreshToken->user_id === null) {

            if($userId === null) {
                throw new InvalidArgumentException('User id cannot be null');
            }

            $refreshToken->user_id = $userId;
        }

        $refreshToken->saveOrFail();

        $tokenData = $this->encoder->encodeData($refreshToken);
        $signature = $this->signer->sign($tokenData);
        return [$refreshToken, $this->encoder->encode($tokenData, $signature)];
    }
}
