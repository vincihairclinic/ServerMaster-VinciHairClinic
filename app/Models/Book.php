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
 * Class Book
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $image
 * @property string $url_image
 * @property string $about_en
 * @property string $about_pt
 * @property string $video_en
 * @property string $url_video_en
 * @property string $video_pt
 * @property string $url_video_pt
 * @property string $video
 * @property string $url_video
 * @property array $faqs_en
 * @property array $faqs_pt
 * @property array $faqs
 * @property string $pre_name_en
 * @property string $pre_name_pt
 * @property string $pre_name
 * @property string $pre_content_en
 * @property string $pre_content_pt
 * @property string $pre_content
 * @property string $pre_image
 * @property string $url_pre_image
 * @property string $post_name_en
 * @property string $post_name_pt
 * @property string $post_name
 * @property string $post_content_en
 * @property string $post_content_pt
 * @property string $post_content
 * @property string $post_image
 * @property string $url_post_image
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection $book_reviews
 * @property Collection $book_reviews_all_langs
 * @property Collection $book_informations
 * @property Collection $book_informations_all_langs
 * @property Collection $book_pre_instructions
 * @property Collection $book_pre_additionals
 * @property Collection $book_post_instructions
 * @property Collection $book_post_additionals
 *
 * @package App\Models
 */
class Book extends ModelBase
{
    protected $table = 'books';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_image',
        'about',
        'url_video_en',
        'url_video_pt',
        'url_video',
        'faqs',
        'pre_name',
        'pre_content',
        'url_pre_image',
        'post_name',
        'post_content',
        'url_post_image',
    ];

    protected $casts = [
        'id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name_en',
        'name_pt',
        'image',
        'about_en',
        'about_pt',
        'video_en',
        'video_pt',
        'faqs_en',
        'faqs_pt',
        'pre_name_en',
        'pre_name_pt',
        'pre_content_en',
        'pre_content_pt',
        'pre_image',
        'post_name_en',
        'post_name_pt',
        'post_content_en',
        'post_content_pt',
        'post_image',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
        'url_image',
        'about',
        'url_video',
        'faqs',
        'pre_name',
        'pre_content',
        'url_pre_image',
        'post_name',
        'post_content',
        'url_post_image',
        'sort',
        'book_reviews',
        'book_informations',
        'book_pre_instructions',
        'book_pre_additionals',
        'book_post_instructions',
        'book_post_additionals',
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
            if(!empty($model->video_en)){
                BlobRepository::fileDelete($model->video_en, 'file');
            }
            if(!empty($model->video_pt)){
                BlobRepository::fileDelete($model->video_pt, 'file');
            }
            if(!empty($model->pre_image)){
                BlobRepository::fileDelete($model->pre_image, 'image');
            }
            if(!empty($model->post_image)){
                BlobRepository::fileDelete($model->post_image, 'image');
            }
        });
    }

    //------------------------------------

    public function getPreContentAttribute()
    {
        return $this->{'pre_content_'.AppConf::$lang};
    }

    public function getPostContentAttribute()
    {
        return $this->{'post_content_'.AppConf::$lang};
    }

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
    }

    public function getAboutAttribute()
    {
        return $this->{'about_'.AppConf::$lang};
    }

    public function getPreNameAttribute()
    {
        return $this->{'pre_name_'.AppConf::$lang};
    }

    public function getPostNameAttribute()
    {
        return $this->{'post_name_'.AppConf::$lang};
    }

    //------------------------------------

    static function getFaqsEn($value = null)
    {
        if(!empty($value)){
            foreach ($value as $i => $v){
                $value[$i]->question = !empty($v->question) ? $v->question : null;
                $value[$i]->answer = !empty($v->answer) ? $v->answer : null;
            }
        }

        return $value;
    }

    public function getFaqsEnAttribute($value)
    {
        $value = !empty($value) ? json_decode($value) : [];
        return self::getFaqsEn($value);
    }

    public function setFaqsEnAttribute($value)
    {
        $value = is_array($value) && !empty($value) ? json_encode(self::getFaqsEn($value), JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['faqs_en'] = !empty($value) ? $value : null;
    }

    //------------------------------------

    static function getFaqsPt($value = null)
    {
        if(!empty($value)){
            foreach ($value as $i => $v){
                $value[$i]->question = !empty($v->question) ? $v->question : null;
                $value[$i]->answer = !empty($v->answer) ? $v->answer : null;
            }
        }

        return $value;
    }

    public function getFaqsPtAttribute($value)
    {
        $value = !empty($value) ? json_decode($value) : [];
        return self::getFaqsPt($value);
    }

    public function setFaqsPtAttribute($value)
    {
        $value = is_array($value) && !empty($value) ? json_encode(self::getFaqsPt($value), JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['faqs_pt'] = !empty($value) ? $value : null;
    }

    //------------------------------------

    public function getFaqsAttribute($value)
    {
        return $this->{'faqs_'.AppConf::$lang};
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

    //-------------------------------

    public function getUrlPreImageAttribute()
    {
        return !empty($this->pre_image) ? Application::storageImageAsset($this->pre_image) : null;
    }

    public function setUrlPreImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('pre_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('pre_image'), 'image');
            }
        }

        $this->attributes['pre_image'] = $value;
    }

    public function setPreImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('pre_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('pre_image'), 'image');
            }
        }

        $this->attributes['pre_image'] = $value;
    }

    //-------------------------------

    public function getUrlPostImageAttribute()
    {
        return !empty($this->post_image) ? Application::storageImageAsset($this->post_image) : null;
    }

    public function setUrlPostImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('post_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('post_image'), 'image');
            }
        }

        $this->attributes['post_image'] = $value;
    }

    public function setPostImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('post_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('post_image'), 'image');
            }
        }

        $this->attributes['post_image'] = $value;
    }

    //-------------------------------

    public function getUrlVideoEnAttribute()
    {
        return !empty($this->video_en) ? Application::storageFileAsset($this->video_en) : null;
    }

    public function setUrlVideoEnAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('video_en') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video_en'), 'file');
            }
        }

        $this->attributes['video_en'] = $value;
    }

    public function setVideoEnAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('video_en') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video_en'), 'file');
            }
        }

        $this->attributes['video_en'] = $value;
    }

    //-------------------------------

    public function getUrlVideoPtAttribute()
    {
        return !empty($this->video_pt) ? Application::storageFileAsset($this->video_pt) : null;
    }

    public function setUrlVideoPtAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('video_pt') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video_pt'), 'file');
            }
        }

        $this->attributes['video_pt'] = $value;
    }

    public function setVideoPtAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('video_pt') != $value) {
                BlobRepository::filesDelete($this->getOriginal('video_pt'), 'file');
            }
        }

        $this->attributes['video_pt'] = $value;
    }

    //------------------------------------

    public function getUrlVideoAttribute($value)
    {
        return $this->{'url_video_'.AppConf::$lang};
    }

    //------------------------------------

    public function book_reviews()
    {
        return $this->hasMany(BookReview::class)->where('language_key', AppConf::$lang);
    }

    public function book_reviews_all_langs()
    {
        return $this->hasMany(BookReview::class);
    }

    public function book_informations()
    {
        return $this->hasMany(BookInformation::class)->where('language_key', AppConf::$lang);
    }

    public function book_informations_all_langs()
    {
        return $this->hasMany(BookInformation::class);
    }

    public function book_pre_instructions()
    {
        return $this->hasMany(BookPreInstruction::class)->where('language_key', AppConf::$lang);
    }

    public function book_pre_instructions_all_langs()
    {
        return $this->hasMany(BookPreInstruction::class);
    }

    public function book_pre_additionals()
    {
        return $this->hasMany(BookPreAdditional::class)->where('language_key', AppConf::$lang);
    }

    public function book_pre_additionals_all_langs()
    {
        return $this->hasMany(BookPreAdditional::class);
    }

    public function book_post_instructions()
    {
        return $this->hasMany(BookPostInstruction::class)->where('language_key', AppConf::$lang);
    }

    public function book_post_instructions_all_langs()
    {
        return $this->hasMany(BookPostInstruction::class);
    }

    public function book_post_additionals()
    {
        return $this->hasMany(BookPostAdditional::class)->where('language_key', AppConf::$lang);
    }

    public function book_post_additionals_all_langs()
    {
        return $this->hasMany(BookPostAdditional::class);
    }



}