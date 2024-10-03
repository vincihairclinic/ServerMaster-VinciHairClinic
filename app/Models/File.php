<?php

namespace App\Models;

use App\Traits\DynamicHiddenVisibleTrait;

/**
 * Class File
 *
 * @property string $name
 * @property string $mime_type
 * @property integer $size
 * @property $content
 *
 * @package App\Models
 */
class File extends ModelBase
{
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'files';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'content',
        'mime_type',
        'size',
    ];

}