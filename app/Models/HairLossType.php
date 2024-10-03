<?php

namespace App\Models;

use App\AppConf;
use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Support\Str;

/**
 * Class HairLossType
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property int $gender_id
 * @property int $sort
 *
 * @package App\Models
 */
class HairLossType extends ModelBase
{
    protected $table = 'hair_loss_types';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_image',
    ];

    protected $casts = [
        'id' => 'int',
        'gender_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name_en',
        'name_pt',
        'gender_id',
        'image',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
        'gender_id',
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

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
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

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

}