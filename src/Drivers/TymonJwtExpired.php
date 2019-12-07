<?php


namespace UonSoftware\RefreshTokens\Drivers;


use Throwable;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use UonSoftware\RefreshTokens\Contracts\UserJwtExpired;

class TymonJwtExpired implements UserJwtExpired
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    /**
     * TymonJwtExpired constructor.
     *
     * @param \Tymon\JWTAuth\JWTAuth $JWTAuth
     */
    public function __construct(JWTAuth $JWTAuth)
    {
        $this->jwt = $JWTAuth;
    }


    /**
     * @inheritDoc
     */
    public function hasTokenExpired(): bool
    {
        try {
            $this->jwt->parseToken()->authenticate();
            return true;
        } catch (TokenExpiredException $e) {
            return false;
        } // For every other exception just pass through the middleware pipeline
        catch (Throwable $e) {
            return true;
        }
    }
}
