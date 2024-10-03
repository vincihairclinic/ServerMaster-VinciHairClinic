<?php

namespace App\Models;

/**
 * Class OauthAuthCode
 *
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $scopes
 * @property int $revoked
 * @property \Carbon\Carbon $expires_at
 *
 * @property \App\Models\OauthClient $oauth_client
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class OauthAuthCode extends ModelBase
{
    public $incrementing = false;
    public $timestamps = false;

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
        'scopes',
        'revoked',
        'expires_at'
    ];

    //----------------------------------------------------

    public function oauth_client()
    {
        return $this->belongsTo(\App\Models\OauthClient::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
