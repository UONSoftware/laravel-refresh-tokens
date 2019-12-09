<?php


namespace UonSoftware\RefreshTokens\Service;


use RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Config\Repository as Config;
use UonSoftware\RefreshTokens\RefreshToken;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier as Verifier;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenOwnershipsException;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenRepository as Repository;

class RefreshTokenService implements Repository
{
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;
    /**
     * @var \UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier
     */
    protected $refreshTokenVerifier;
    /**
     * @var \UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder
     */
    protected $decoder;


    /**
     * RefreshTokenService constructor.
     *
     * @param \Illuminate\Config\Repository                             $config
     * @param \UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier $verifier
     * @param \UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder  $decoder
     */
    public function __construct(Config $config, Verifier $verifier, Decoder $decoder)
    {
        $this->config = $config;
        $this->refreshTokenVerifier = $verifier;
        $this->decoder = $decoder;
    }

    public function getTokens(int $page, int $perPage): LengthAwarePaginator
    {
        return $this->getTokensForUser($page, $perPage);
    }

    public function getTokensForUser(int $page = 1, int $perPage = 10, ?int $userId = null): LengthAwarePaginator
    {
        $userForeignKey = $this->config->get('refresh_token.user.foreign_key');
        $query = RefreshToken::query();

        if ($userId !== null) {
            $query->where($userForeignKey, '=', $userId);
        }

        return $query->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);
    }

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
    public function revokeToken(string $token, int $user): bool
    {
        $this->refreshTokenVerifier->verify($token);

        /**
         * @var RefreshToken $refreshToken
         */
        [0 => $refreshToken] = $this->decoder->decode($token);

        DB::beginTransaction();
        $foreignKey = $this->config->get('lara_auth.user.foreign_key');
        if ($refreshToken->{$foreignKey} !== $user) {
            throw new RefreshTokenOwnershipsException();
        }

        if (!$refreshToken->delete()) {
            throw new RuntimeException('Error while deleting refresh token');
        }

        return true;
    }
}