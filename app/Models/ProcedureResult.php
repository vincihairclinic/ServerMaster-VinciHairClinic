<?php

namespace App\Models;

use App\AppConf;
use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class ProcedureResult
 *
 * @property int $id
 * @property int $gender_id
 * @property string $title_en
 * @property string $title_pt
 * @property string $title
 * @property string $subtitle_en
 * @property string $subtitle_pt
 * @property string $subtitle
 * @property int $procedure_id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property string $content_en
 * @property string $content_pt
 * @property string $content
 * @property int $sort
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Procedure $procedure
 *
 * @property Collection $procedure_result_videos
 * @property Collection $procedure_result_images
 *
 * @property Collection $tag_procedures
 * @property Collection $tag_hair_types
 * @property Collection $tag_genders
 *
 * @package App\Models
 */
class ProcedureResult extends ModelBase
{
    protected $table = 'procedure_results';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'title',
        'subtitle',
        'name',
        'url_image',
        'content',
    ];

    protected $casts = [
        'id' => 'int',
        'gender_id' => 'int',
        'procedure_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'gender_id',
        'title_en',
        'title_pt',
        'subtitle_en',
        'subtitle_pt',
        'procedure_id',
        'date',
        'name_en',
        'name_pt',
        'image',
        'content_en',
        'content_pt',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'gender_id',
        'title',
        'subtitle',
        'procedure_id',
        'date',
        'procedure',
        'name',
        'url_image',
        'content',
        'created_at',
        'procedure_result_videos',
        'procedure_result_images',
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

    public function getTitleAttribute()
    {
        return $this->{'title_'.AppConf::$lang};
    }

    public function getSubtitleAttribute()
    {
        return $this->{'subtitle_'.AppConf::$lang};
    }

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
    }

    public function getContentAttribute()
    {
        return $this->{'content_'.AppConf::$lang};
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

    //------------------------------------

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function procedure_result_videos()
    {
        return $this->hasMany(ProcedureResultVideo::class)->where('language_key', AppConf::$lang);
    }

    public function procedure_result_images()
    {
        return $this->hasMany(ProcedureResultImage::class);
    }

    //------------------------------------

    public function tag_procedures()
    {
        return $this->belongsToMany(Procedure::class, 'tag_procedure_result_procedures');
    }

    public function tag_hair_types()
    {
        return $this->belongsToMany(HairType::class, 'tag_procedure_result_hair_types');
    }

    public function tag_genders()
    {
        return $this->belongsToMany(Gender::class, 'tag_procedure_result_genders');
    }


}