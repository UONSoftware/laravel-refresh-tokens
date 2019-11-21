<?php


use App\User;
use UonSoftware\RefreshTokens\RefreshToken;
use UonSoftware\RefreshTokens\Drivers\TymonJwtSigner;
use UonSoftware\RefreshTokens\Drivers\TymonJwtExpired;
use UonSoftware\RefreshTokens\Drivers\TymonAuthenticate;

return [
    'model' => RefreshToken::class,
    'user' => [
        'model' => User::class,
        'foreign_key' => 'user_id',
        'id' => 'id',
        'key_type' => 'unsignedBigInteger',
    ],
    'token_length' => 200,
    'refresh_token_ttl' => env('JWT_REFRESH_TTL', 60 * 24 * 7),
    'jwt_expired' => TymonJwtExpired::class,
    'token_signer' => TymonJwtSigner::class,
    'authenticate' => TymonAuthenticate::class,
    'header' => 'X-Refresh-Token',
    'resource' => 'UserResource'
];
