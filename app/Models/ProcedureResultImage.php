<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Class ProcedureResultImage
 *
 * @property int $id
 * @property int $procedure_result_id
 * @property string $befor_image
 * @property string $url_befor_image
 * @property string $after_image
 * @property string $url_after_image
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class ProcedureResultImage extends ModelBase
{
    protected $table = 'procedure_result_images';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_befor_image',
        'url_after_image',
    ];

    protected $casts = [
        'id' => 'int',
        'procedure_result_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'procedure_result_id',
        'name',
        'befor_image',
        'after_image',
        'content',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'procedure_result_id',
        'name',
        'url_befor_image',
        'url_after_image',
        'content',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->befor_image)){
                BlobRepository::fileDelete($model->befor_image, 'image');
            }
            if(!empty($model->after_image)){
                BlobRepository::fileDelete($model->after_image, 'image');
            }
        });
    }

    //------------------------------------

    public function getUrlBeforImageAttribute()
    {
        return !empty($this->befor_image) ? Application::storageImageAsset($this->befor_image) : null;
    }

    public function setUrlBeforImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('befor_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('befor_image'), 'image');
            }
        }

        $this->attributes['befor_image'] = $value;
    }

    public function setBeforImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('befor_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('befor_image'), 'image');
            }
        }

        $this->attributes['befor_image'] = $value;
    }

    //------------------------------------

    public function getUrlAfterImageAttribute()
    {
        return !empty($this->after_image) ? Application::storageImageAsset($this->after_image) : null;
    }

    public function setUrlAfterImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('after_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('after_image'), 'image');
            }
        }

        $this->attributes['after_image'] = $value;
    }

    public function setAfterImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('after_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('after_image'), 'image');
            }
        }

        $this->attributes['after_image'] = $value;
    }

    //------------------------------------


}