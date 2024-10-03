<?php

namespace App\Models;

use App\AppConf;
use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class Country
 *
 * @property int $id
 * @property int $territory_id
 * @property string $name
 * @property string $phone_code
 * @property string $flag_image
 * @property string $url_flag_image
 * @property string $area_image
 * @property string $url_area_image
 * @property string $host
 * @property string $shop_url
 * @property int $sort
 *
 * @property Territory $territory
 * @property Collection $clinics
 *
 * @package App\Models
 */
class Country extends ModelBase
{
    protected $table = 'countries';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_flag_image',
        'url_area_image',
    ];

    protected $casts = [
        'id' => 'int',
        'territory_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'territory_id',
        'name',
        'phone_code',
        'flag_image',
        'area_image',
        'host',
        'shop_url',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'territory_id',
        'name',
        'phone_code',
        'host',
        'shop_url',
        'url_flag_image',
        'url_area_image',

        'territory',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->flag_image)){
                BlobRepository::fileDelete($model->flag_image, 'image');
            }
        });
    }

    //------------------------------------

    public function getUrlFlagImageAttribute()
    {
        return !empty($this->flag_image) ? Application::storageImageAsset($this->flag_image) : null;
    }

    public function setUrlFlagImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('flag_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('flag_image'), 'image');
            }
        }

        $this->attributes['flag_image'] = $value;
    }

    public function setFlagImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('flag_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('flag_image'), 'image');
            }
        }

        $this->attributes['flag_image'] = $value;
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

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

}