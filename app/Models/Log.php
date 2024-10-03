<?php

namespace App\Models;

use App\Application;

/**
 * Class Log
 * 
 * @property int $id
 * @property string $event
 * @property string $status
 * @property string $proc
 * @property string $description
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class Log extends ModelBase
{
    const STATUS_DANGER = 'danger';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_ERROR = 'error';

    const EVENT_TRY_CATCH = 'try_catch';
    const EVENT_IF_ERROR = 'if_error';
    const EVENT_HACKER = 'hacker';

	public $timestamps = false;

    protected $casts = [
        'id' => 'int',
    ];

	protected $fillable = [
		'event',
		'status',
        'proc',
		'description'
	];

    static function add($params, $procName = null)
    {
        $params['proc'] = !empty($procName) ? $procName : Application::$procName;
        if(!empty($params['description'])){
            $params['description'] = mb_strimwidth($params['description'], 0, 65000);
        }
        self::create($params);
    }
}
