<?php

namespace App\Models;

use App\AppConf;
use App\Traits\DynamicHiddenVisibleTrait;

/**
 * Class Localization
 *
 * @property int $id
 * @property string $key
 * @property string $value_en
 * @property string $value_pt
 * @property string $value
 *
 * @package App\Models
 */
class Localization extends ModelBase
{
    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'value',
    ];

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'key',
        'value_en',
        'value_pt',
    ];

    static $publicColumns = [
        'key',
        'value',
    ];

    //-----------------

    public function getValueAttribute()
    {
        return $this->{'value_'.AppConf::$lang};
    }

    //-----------------

    static function trans($key, $lang_id = 1)
    {
        $prevLang = AppConf::$lang;
        AppConf::$lang = $lang_id == 2 ? 'ro' : 'en';

        $result = Localization::where('key', $key)->first();
        $result = !empty($result) ? $result->value : null;

        AppConf::$lang = $prevLang;

        return $result;
    }

}
