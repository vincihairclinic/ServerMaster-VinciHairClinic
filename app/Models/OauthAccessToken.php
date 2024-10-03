<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class OauthAccessToken
 *
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $name
 * @property string $scopes
 * @property int $revoked
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $expires_at
 *
 * @property OauthClient $oauth_client
 * @property User $user
 * @property Collection $oauth_refresh_tokens
 *
 * @package App\Models
 */
class OauthAccessToken extends ModelBase
{
    public $incrementing = false;

    protected $casts = [
        'user_id'   => 'int',
        'client_id' => 'int',
        'revoked'   => 'int'
    ];

    protected $dates = [
        'expires_at'
    ];

    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'scopes',
        'revoked',
        'expires_at'
    ];

    //----------------------------------------------------

    public function oauth_client()
    {
        return $this->belongsTo(OauthClient::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oauth_refresh_tokens()
    {
        return $this->hasMany(OauthRefreshToken::class, 'access_token_id');
    }
}
