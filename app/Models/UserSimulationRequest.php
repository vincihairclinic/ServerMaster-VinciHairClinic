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
 * Class UserSimulationRequest
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property string $full_name
 * @property string $phone_number
 * @property int $country_id
 * @property int $simulation_request_type_id
 * @property string $image
 * @property string $url_image
 * @property string $hair_front_image
 * @property string $url_hair_front_image
 * @property string $hair_side_image
 * @property string $url_hair_side_image
 * @property string $hair_back_image
 * @property string $url_hair_back_image
 * @property string $hair_top_image
 * @property string $url_hair_top_image
 * @property Carbon $created_at
 * @property bool $is_request_simulation_checked
 *
 * @property Collection $podcast_episodes
 * @property Collection $user_viewed_podcast
 *
 * @package App\Models
 */
class UserSimulationRequest extends ModelBase
{
    protected $table = 'user_simulation_requests';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'url_image',
        'url_hair_front_image',
        'url_hair_side_image',
        'url_hair_back_image',
        'url_hair_top_image',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'country_id' => 'int',
        'simulation_request_type_id' => 'int',
        'is_request_simulation_checked' => 'bool',
    ];

    protected $fillable = [
        'user_id',
        'email',
        'full_name',
        'phone_number',
        'country_id',
        'simulation_request_type_id',
        'image',
        'hair_front_image',
        'hair_side_image',
        'hair_back_image',
        'hair_top_image',
        'is_request_simulation_checked',
    ];

    static $publicColumns = [
        'id',
        'user_id',
        'email',
        'full_name',
        'phone_number',
        'country_id',
        'simulation_request_type_id',
        'image',
        'url_image',
        'url_hair_front_image',
        'url_hair_side_image',
        'url_hair_back_image',
        'url_hair_top_image',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            if(!empty($model->image)){
                BlobRepository::fileDelete($model->image, 'image');
            }
            if(!empty($model->hair_front_image)){
                BlobRepository::fileDelete($model->hair_front_image, 'image');
            }
            if(!empty($model->hair_side_image)){
                BlobRepository::fileDelete($model->hair_side_image, 'image');
            }
            if(!empty($model->hair_back_image)){
                BlobRepository::fileDelete($model->hair_back_image, 'image');
            }
            if(!empty($model->hair_top_image)){
                BlobRepository::fileDelete($model->hair_top_image, 'image');
            }
        });
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

    //---------------------------------------------------------

    public function getUrlHairFrontImageAttribute()
    {
        return !empty($this->hair_front_image) ? Application::storageImageAsset($this->hair_front_image) : null;
    }

    public function setUrlHairFrontImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('hair_front_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_front_image'), 'image');
            }
        }

        $this->attributes['hair_front_image'] = $value;
    }

    public function setHairFrontImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_front_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_front_image'), 'image');
            }
        }

        $this->attributes['hair_front_image'] = $value;
    }

    //---------------------------------------------------------

    public function getUrlHairSideImageAttribute()
    {
        return !empty($this->hair_side_image) ? Application::storageImageAsset($this->hair_side_image) : null;
    }

    public function setUrlHairSideImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('hair_side_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_side_image'), 'image');
            }
        }

        $this->attributes['hair_side_image'] = $value;
    }

    public function setHairSideImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_side_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_side_image'), 'image');
            }
        }

        $this->attributes['hair_side_image'] = $value;
    }

    //---------------------------------------------------------

    public function getUrlHairBackImageAttribute()
    {
        return !empty($this->hair_back_image) ? Application::storageImageAsset($this->hair_back_image) : null;
    }

    public function setUrlHairBackImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('hair_back_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_back_image'), 'image');
            }
        }

        $this->attributes['hair_back_image'] = $value;
    }

    public function setHairBackImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_back_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_back_image'), 'image');
            }
        }

        $this->attributes['hair_back_image'] = $value;
    }

    //---------------------------------------------------------

    public function getUrlHairTopImageAttribute()
    {
        return !empty($this->hair_top_image) ? Application::storageImageAsset($this->hair_top_image) : null;
    }

    public function setUrlHairTopImageAttribute($value)
    {
        if(Str::startsWith($value, config('app.url'))){
            $value = explode('/', $value);
            $value = $value[count($value)-1];
            $value = mb_strpos($value, '.') !== false ? $value : null;
        }

        if(!empty($this->id)) {
            if ($this->getOriginal('hair_top_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_top_image'), 'image');
            }
        }

        $this->attributes['hair_top_image'] = $value;
    }

    public function setHairTopImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_top_image') != $value) {
                BlobRepository::filesDelete($this->getOriginal('hair_top_image'), 'image');
            }
        }

        $this->attributes['hair_top_image'] = $value;
    }

    //---------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function simulation_request_type()
    {
        return $this->belongsTo(SimulationRequestType::class);
    }
}