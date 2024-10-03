<?php

namespace App\Models;

use App\AppConf;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Gender
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property int $sort
 *
 * @package App\Models
 */
class Gender extends ModelBase
{
    protected $table = 'genders';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name_en',
        'name_pt',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    //------------------------------------

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
    }

    //------------------------------------

}