<?php

namespace UonSoftware\RefreshTokens;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class RefreshToken
 *
 * @package UonSoftware\RefreshTokens
 * @property integer $id
 * @property string $token
 * @property \Carbon\Carbon $expires
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer|string $user_id
 */
class RefreshToken extends Model
{
    protected $table = 'refresh_tokens';
    
    protected $fillable = [
        'token',
        'expires',
        'user_id',
    ];
    
    protected $hidden = [
        'id',
        'token',
        'expires',
        'user_id',
    ];
    
    protected $casts = [
        'expires' => 'datetime',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        $user = config('refresh_tokens.user');
        
        return $this->belongsTo($user['model'], $user['foreign_key'], $user['id']);
    }
}
