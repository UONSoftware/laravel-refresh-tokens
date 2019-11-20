<?php


namespace UonSoftware\RefreshTokens\Drivers;


use Tymon\JWTAuth\JWTAuth;
use UonSoftware\RefreshTokens\Contracts\Authenticate;

class TymonAuthenticate implements Authenticate
{
    private $jwt;
    public function __construct(JWTAuth $jwt) {
        $this->jwt = $jwt;
    }

    public function authenticate(string $jwt)
    {
        return $this->jwt->setToken($jwt)->authenticate();
    }
}
