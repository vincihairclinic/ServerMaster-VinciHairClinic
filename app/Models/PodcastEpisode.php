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
 * Class PodcastEpisode
 *
 * @property int $id
 * @property int $podcast_id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property string $file_en
 * @property string $url_file_en
 * @property string $file_pt
 * @property string $url_file_pt
 * @property string $url_file
 * @property int $duration_min
 * @property string $content_en
 * @property string $content_pt
 * @property string $content
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Podcast $podcast
 *
 * @property Collection $tag_procedures
 * @property Collection $tag_hair_types
 * @property Collection $tag_genders
 *
 * @package App\Models
 */
class PodcastEpisode extends ModelBase
{
    protected $table = 'podcast_episodes';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_image',
        'url_file_en',
        'url_file_pt',
        'url_file',
        'content',
    ];

    protected $casts = [
        'id' => 'int',
        'podcast_id' => 'int',
        'duration_min' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'podcast_id',
        'name_en',
        'name_pt',
        'image',
        'file_en',
        'file_pt',
        'duration_min',
        'content_en',
        'content_pt',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
        'url_image',
        'url_file',
        'duration_min',
        'created_at',
        'content',
        'procedures',
        'podcast',
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
            if(!empty($model->file_en)){
                BlobRepository::fileDelete($model->file_en, 'file');
            }
            if(!empty($model->file_pt)){
                BlobRepository::fileDelete($model->file_pt, 'file');
            }
        });
    }

    //-------------------------------

    public function getUrlFileEnAttribute()
    {
        return !empty($this->file_en) ? Application::storageFileAsset($this->file_en) : null;
    }

    public function setUrlFileEnAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('file_en') != $value) {
                BlobRepository::filesDelete($this->getOriginal('file_en'), 'file');
            }
        }

        $this->attributes['file_en'] = $value;
    }

    public function setFileEnAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('file_en') != $value) {
                BlobRepository::filesDelete($this->getOriginal('file_en'), 'file');
            }
        }

        $this->attributes['file_en'] = $value;
    }

    //-------------------------------

    public function getUrlFilePtAttribute()
    {
        return !empty($this->file_pt) ? Application::storageFileAsset($this->file_pt) : null;
    }

    public function setUrlFilePtAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('file_pt') != $value) {
                BlobRepository::filesDelete($this->getOriginal('file_pt'), 'file');
            }
        }

        $this->attributes['file_pt'] = $value;
    }

    public function setFilePtAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('file_pt') != $value) {
                BlobRepository::filesDelete($this->getOriginal('file_pt'), 'file');
            }
        }

        $this->attributes['file_pt'] = $value;
    }

    //------------------------------------

    public function getUrlFileAttribute($value)
    {
        return $this->{'url_file_'.AppConf::$lang};
    }

    //------------------------------------

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

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    //------------------------------------

    public function tag_procedures()
    {
        return $this->belongsToMany(Procedure::class, 'tag_podcast_episode_procedures');
    }

    public function tag_hair_types()
    {
        return $this->belongsToMany(HairType::class, 'tag_podcast_episode_hair_types');
    }

    public function tag_genders()
    {
        return $this->belongsToMany(Gender::class, 'tag_podcast_episode_genders');
    }

}