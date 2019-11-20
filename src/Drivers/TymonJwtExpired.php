<?php


namespace UonSoftware\RefreshTokens\Drivers;


use Throwable;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use UonSoftware\RefreshTokens\Contracts\UserJwtExpired;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class TymonJwtExpired implements UserJwtExpired
{
    protected $jwt;

    public function __construct(JWTAuth $JWTAuth) {
        $this->jwt = $JWTAuth;
    }


    public function hasTokenExpired(): bool
    {
        try {
            $this->jwt->parseToken()->authenticate();
            return true;
        }catch (TokenExpiredException $e) {
            return false;
        }
        // For every other exception just pass through the middleware pipeline
        catch (Throwable $e) {
            return true;
        }
    }
}
