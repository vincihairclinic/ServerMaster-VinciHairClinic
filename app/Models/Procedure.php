<?php

namespace App\Models;

use App\AppConf;
use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Class Procedure
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property int $sort
 *
 * @property Collection $users
 * @property Collection $articles
 *
 * @package App\Models
 */
class Procedure extends ModelBase
{
    protected $table = 'procedures';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_image',
    ];

    protected $casts = [
        'id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name_en',
        'name_pt',
        'image',
        'sort',
    ];

    static $publicColumns = [
        'id',
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

    //------------------------------------

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_procedure');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_procedure');
    }

}