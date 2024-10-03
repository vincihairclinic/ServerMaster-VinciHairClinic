<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Class ProcedureResultVideo
 *
 * @property int $id
 * @property int $procedure_result_id
 * @property string $language_key
 * @property string $name
 * @property string $video
 * @property string $url_video
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class ProcedureResultVideo extends ModelBase
{
    protected $table = 'procedure_result_videos';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_video',
    ];

    protected $casts = [
        'id' => 'int',
        'procedure_result_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'procedure_result_id',
        'language_key',
        'name',
        'video',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'procedure_result_id',
        'name',
        'url_video',
    ];

    protected $attributes = [
        'language_key' => 'en',
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->video)){
                BlobRepository::fileDelete($model->video, 'file');
            }
        });
    }

    //-------------------------------

    public function getUrlVideoAttribute()
    {
        return !empty($this->video) ? Application::storageFileAsset($this->video) : null;
    }

    public function setUrlVideoAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('video') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video'), 'file');
            }
        }

        $this->attributes['video'] = $value;
    }

    public function setVideoAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('video') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video'), 'file');
            }
        }

        $this->attributes['video'] = $value;
    }

    //------------------------------------

}