<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BookPreAdditional
 *
 * @property int $id
 * @property int $book_id
 * @property string $language_key
 * @property string $title
 * @property string $content
 * @property array $images
 * @property array $url_images
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Book $book
 * @property Collection $book_pre_additional_items
 *
 * @package App\Models
 */
class BookPreAdditional extends ModelBase
{
    protected $table = 'book_pre_additionals';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_images',
    ];

    protected $casts = [
        'id' => 'int',
        'book_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'book_id',
        'language_key',
        'title',
        'content',
        'images',
        'sort',
    ];

    static $publicColumns = [
        'title',
        'url_images',
        'content',
        'book_pre_additional_items',
    ];

    protected $attributes = [
        'language_key' => 'en',
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->images)){
                BlobRepository::filesDelete($model->images, 'image');
            }
        });
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

    //------------------------------------

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function book_pre_additional_items()
    {
        return $this->hasMany(BookPreAdditionalItem::class);
    }
}