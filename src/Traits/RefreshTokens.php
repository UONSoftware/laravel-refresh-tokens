<?php


namespace UonSoftware\RefreshTokens\Traits;


use Illuminate\Database\Eloquent\Relations\HasMany;

trait RefreshTokens
{
    public function refreshTokens(): HasMany
    {
        $config = config('refresh_tokens');
        return $this->hasMany($config['user.model'], $config['user.foreign_key'], $config['user.id']);
    }
}

