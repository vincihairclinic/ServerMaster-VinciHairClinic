<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OauthClient
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property int $personal_access_client
 * @property int $password_client
 * @property int $revoked
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property \App\Models\User $user
 * @property Collection $oauth_access_tokens
 * @property Collection $oauth_auth_codes
 * @property Collection $oauth_personal_access_clients
 *
 * @package App\Models
 */
class OauthClient extends ModelBase
{
    protected $casts = [
        'user_id'                => 'int',
        'personal_access_client' => 'int',
        'password_client'        => 'int',
        'revoked'                => 'int'
    ];

    protected $hidden = [
        'secret'
    ];

    protected $fillable = [
        'user_id',
        'name',
        'secret',
        'redirect',
        'personal_access_client',
        'password_client',
        'revoked'
    ];

    //----------------------------------------------------

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function oauth_access_tokens()
    {
        return $this->hasMany(\App\Models\OauthAccessToken::class, 'client_id');
    }

    public function oauth_auth_codes()
    {
        return $this->hasMany(\App\Models\OauthAuthCode::class, 'client_id');
    }

    public function oauth_personal_access_clients()
    {
        return $this->hasMany(\App\Models\OauthPersonalAccessClient::class, 'client_id');
    }
}
