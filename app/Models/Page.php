<?php

namespace App\Models;

use App\Traits\DynamicHiddenVisibleTrait;

/**
 * Class Page
 *
 * @property int $id
 * @property string $route
 * @property string $name
 * @property string $html
 *
 * @package App\Models
 */
class Page extends ModelBase
{
    protected $table = 'pages';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'route',
        'name',
        'html',
    ];

}