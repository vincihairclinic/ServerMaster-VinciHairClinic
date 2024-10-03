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
 * Class Product
 *
 * @property int $id
 * @property int $product_category_id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property array $images
 * @property array $url_images
 * @property array $shop_now_urls
 * @property string $about_en
 * @property string $about_pt
 * @property string $video_en
 * @property string $url_video_en
 * @property string $video_pt
 * @property string $url_video_pt
 * @property string $video
 * @property string $url_video
 * @property string $video_name_en
 * @property string $video_name_pt
 * @property string $video_name
 * @property array $faqs_en
 * @property array $faqs_pt
 * @property array $faqs
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ProductCategory $product_category
 *
 * @property Collection $product_reviews
 * @property Collection $product_reviews_all_langs
 *
 * @package App\Models
 */
class Product extends ModelBase
{
    protected $table = 'products';

    static $hideEmptyShopNowUrl = false;

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'url_images',
        'about',
        'faqs',
        'url_video_en',
        'url_video_pt',
        'url_video',
        'video_name',
    ];

    protected $casts = [
        'id' => 'int',
        'product_category_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'product_category_id',
        'name_en',
        'name_pt',
        'images',
        'shop_now_urls',
        'about_en',
        'about_pt',
        'video_en',
        'video_pt',
        'video_name_en',
        'video_name_pt',
        'faqs_en',
        'faqs_pt',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'product_category_id',
        'product_category',
        'name',
        'url_images',
        'shop_now_urls',
        'about',
        'url_video',
        'video_name',
        'faqs',
        'sort',
        'product_reviews',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->video_en)){
                BlobRepository::fileDelete($model->video_en, 'file');
            }
            if(!empty($model->video_pt)){
                BlobRepository::fileDelete($model->video_pt, 'file');
            }
            if(!empty($model->images)){
                BlobRepository::filesDelete($model->images, 'image');
            }
        });
    }

    //------------------------------------

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
    }

    public function getAboutAttribute()
    {
        return $this->{'about_'.AppConf::$lang};
    }

    public function getVideoNameAttribute()
    {
        return $this->{'video_name_'.AppConf::$lang};
    }

    //------------------------------------

    static function getShopNowUrls($value = null)
    {
        $result = [];
        foreach (Country::all() as $country){
            $result[] = (object)[
                'country_id' => $country->id,
                'shop_now_url' => null,
            ];
        }

        if(!empty($value)){
            foreach ($value as $i => $v){
                if(!self::$hideEmptyShopNowUrl || !empty($v->shop_now_url)){
                    $result[$i]->country_id = !empty($v->country_id) ? $v->country_id : $result[$i]->country_id;
                    $result[$i]->shop_now_url = !empty($v->shop_now_url) ? $v->shop_now_url : $result[$i]->shop_now_url;
                }else{
                    $result[$i] = null;
                }
            }
        }

        return array_values(array_filter($result));
    }

    public function getShopNowUrlsAttribute($value)
    {
        $value = !empty($value) ? json_decode($value) : [];
        return self::getShopNowUrls($value);
    }

    public function setShopNowUrlsAttribute($value)
    {
        $value = is_array($value) && !empty($value) ? json_encode(self::getShopNowUrls($value), JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['shop_now_urls'] = !empty($value) ? $value : null;
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

    public function getUrlImagesAttribute()
    {
        $value = [];
        foreach ($this->images as $image){
            $value[] = Application::storageImageAsset($image);
        }
        return $value;
    }

    public function setUrlImagesAttribute($value)
    {
        if(!empty($value) && is_array($value)){
            foreach ($value as $i => $v){
                $value[$i] = !empty($v) ? $v: null;
            }
            $value = array_values(array_filter($value));
        }

        if(!empty($this->id)) {
            foreach ($this->getOriginal('images') as $old_image) {
                if (!in_array($old_image, (array)$value)) {
                    BlobRepository::filesDelete($old_image, 'image');
                }
            }
        }

        $value = is_array($value) && !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['images'] = !empty($value) ? $value : null;
    }

    public function getImagesAttribute($value)
    {
        $value = !empty($value) ? json_decode($value) : [];
        return !empty($value) ? $value : [];
    }

    public function setImagesAttribute($value)
    {
        if(!empty($this->id)) {
            foreach ($this->getOriginal('images') as $old_image) {
                if (!in_array($old_image, (array)$value)) {
                    BlobRepository::filesDelete($old_image, 'image');
                }
            }
        }

        $value = is_array($value) && !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['images'] = !empty($value) ? $value : null;
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

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function product_reviews()
    {
        return $this->hasMany(ProductReview::class)->where('language_key', AppConf::$lang);
    }

    public function product_reviews_all_langs()
    {
        return $this->hasMany(ProductReview::class);
    }


}