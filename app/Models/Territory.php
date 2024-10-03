<?php

namespace App\Models;

use App\AppConf;
use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class Territory
 *
 * @property int $id
 * @property string $name
 * @property string $area_image
 * @property string $url_area_image
 * @property int $sort
 *
 * @property Collection $countries
 *
 * @package App\Models
 */
class Territory extends ModelBase
{
    protected $table = 'territories';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_area_image',
    ];

    protected $casts = [
        'id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name',
        'area_image',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
        'url_area_image',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->area_image)){
                BlobRepository::fileDelete($model->area_image, 'image');
            }
        });
    }

    //------------------------------------

    public function getUrlAreaImageAttribute()
    {
        return !empty($this->area_image) ? Application::storageImageAsset($this->area_image) : null;
    }

    public function setUrlAreaImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('area_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('area_image'), 'image');
            }
        }

        $this->attributes['area_image'] = $value;
    }

    public function setAreaImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('area_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('area_image'), 'image');
            }
        }

        $this->attributes['area_image'] = $value;
    }

    //--------------------------------------

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

}