<?php


namespace UonSoftware\RefreshTokens\Service;


use Illuminate\Support\Facades\DB;
use Illuminate\Config\Repository as Config;
use UonSoftware\RefreshTokens\RefreshToken;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier as Verifier;
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
     *
     * @param string $token
     *
     * @return bool
     */
    public function revokeToken(string $token): bool
    {
        $this->refreshTokenVerifier->verify($token);
        [2 => $token] = $this->decoder->decode($token, false);

        DB::beginTransaction();

        $numOfRows = RefreshToken::query()
            ->where('token', '=', $token)
            ->delete();

        if ($numOfRows === 0) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }
}