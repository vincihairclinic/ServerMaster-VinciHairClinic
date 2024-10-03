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
 * Class Article
 *
 * @property int $id
 * @property int $article_category_id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property string $content_en
 * @property string $content_pt
 * @property string $content
 * @property bool $is_for_male
 * @property bool $is_for_female
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ArticleCategory $article_category
 *
 * @property Collection $procedures
 *
 * @property Collection $tag_procedures
 * @property Collection $tag_hair_types
 * @property Collection $tag_genders
 *
 * @package App\Models
 */
class Article extends ModelBase
{
    protected $table = 'articles';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_image',
        'content',
    ];

    protected $casts = [
        'id' => 'int',
        'article_category_id' => 'int',
        'is_for_male' => 'bool',
        'is_for_female' => 'bool',
        'sort' => 'int',
    ];

    protected $fillable = [
        'article_category_id',
        'name_en',
        'name_pt',
        'image',
        'content_en',
        'content_pt',
        'is_for_male',
        'is_for_female',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'article_category_id',
        'article_category',
        'name',
        'url_image',
        'is_for_male',
        'is_for_female',
        'created_at',
        'content',
        'procedures',
    ];

    protected $attributes = [
        'sort' => 4294967295,
        'is_for_male' => 0,
        'is_for_female' => 0,
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

    public function article_category()
    {
        return $this->belongsTo(ArticleCategory::class);
    }

    public function procedures()
    {
        return $this->belongsToMany(Procedure::class, 'article_procedure');
    }

    //------------------------------------

    public function tag_procedures()
    {
        return $this->belongsToMany(Procedure::class, 'tag_article_procedures');
    }

    public function tag_hair_types()
    {
        return $this->belongsToMany(HairType::class, 'tag_article_hair_types');
    }

    public function tag_genders()
    {
        return $this->belongsToMany(Gender::class, 'tag_article_genders');
    }

}