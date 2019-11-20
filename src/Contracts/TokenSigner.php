<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Illuminate\Contracts\Auth\Authenticatable;

interface TokenSigner
{
    public function sign(Authenticatable $user): string;
}
