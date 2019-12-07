<?php


namespace UonSoftware\RefreshTokens\Contracts;


use Illuminate\Http\Request;

interface Authenticate
{
    /**
     * After refresh toke is regenerated request will proceed
     * For that user must be logged in, therefor this method will be
     * called to authenticate the user
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $jwt
     *
     * @return void
     */
    public function authenticate(Request $request, string $jwt): void;
}
