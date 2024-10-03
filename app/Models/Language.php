<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Support\Str;

/**
 * Class Language
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property int $sort
 *
 * @package App\Models
 */
class Language extends ModelBase
{
    protected $table = 'languages';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_image',
    ];

    protected $casts = [
        'id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'key',
        'name',
        'image',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'key',
        'name',
        'url_image',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->image)){
                BlobRepository::fileDelete($model->image, 'image');
            }
        });
    }

    //------------------------------------

    public function getUrlImageAttribute()
    {
        return !empty($this->image) ? Application::storageImageAsset($this->image) : null;
    }

    public function setUrlImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('image'), 'image');
            }
        }

        $this->attributes['image'] = $value;
    }

    public function setImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('image'), 'image');
            }
        }

        $this->attributes['image'] = $value;
    }

}