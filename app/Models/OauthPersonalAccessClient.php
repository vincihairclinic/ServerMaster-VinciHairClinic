<?php

namespace App\Models;

/**
 * Class OauthPersonalAccessClient
 * 
 * @property int $id
 * @property int $client_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\OauthClient $oauth_client
 *
 * @package App\Models
 */
class OauthPersonalAccessClient extends ModelBase
{
	protected $casts = [
		'client_id' => 'int'
	];

	protected $fillable = [
		'client_id'
	];

    //----------------------------------------------------

	public function oauth_client()
	{
		return $this->belongsTo(\App\Models\OauthClient::class, 'client_id');
	}
}
