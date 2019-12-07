<?php


namespace UonSoftware\RefreshTokens\Drivers;


use Tymon\JWTAuth\JWTAuth;
use Illuminate\Contracts\Auth\Authenticatable;
use UonSoftware\RefreshTokens\Contracts\TokenSigner;

class TymonJwtSigner implements TokenSigner
{
    private $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function sign(Authenticatable $user): string
    {
        return $this->jwt->fromUser($user);
    }
}
