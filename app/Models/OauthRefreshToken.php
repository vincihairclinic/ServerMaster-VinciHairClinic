<?php

namespace App\Models;

/**
 * Class OauthRefreshToken
 * 
 * @property string $id
 * @property string $access_token_id
 * @property int $revoked
 * @property \Carbon\Carbon $expires_at
 * 
 * @property \App\Models\OauthAccessToken $oauth_access_token
 *
 * @package App\Models
 */
class OauthRefreshToken extends ModelBase
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'revoked' => 'int'
	];

	protected $dates = [
		'expires_at'
	];

	protected $fillable = [
		'access_token_id',
		'revoked',
		'expires_at'
	];

    //----------------------------------------------------

	public function oauth_access_token()
	{
		return $this->belongsTo(\App\Models\OauthAccessToken::class, 'access_token_id');
	}
}
