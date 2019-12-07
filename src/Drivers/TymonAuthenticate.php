<?php


namespace UonSoftware\RefreshTokens\Drivers;


use Illuminate\Http\Request;
use UonSoftware\RefreshTokens\Contracts\Authenticate;

class TymonAuthenticate implements Authenticate
{

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request, string $jwt): void
    {
        $request->headers->replace(['Authorization' => 'Bearer ' . $jwt]);
    }
}
