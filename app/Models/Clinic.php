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
 * Class Country
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_pt
 * @property string $name
 * @property string $about_en
 * @property string $about_pt
 * @property string $about
 * @property array $benefits_en
 * @property array $benefits_pt
 * @property array $benefits
 * @property string $about_clinic_location_en
 * @property string $about_clinic_location_pt
 * @property string $about_clinic_location
 * @property int $country_id
 * @property string $image
 * @property string $url_image
 * @property array $images
 * @property array $url_images
 * @property string $address
 * @property string $postcode
 * @property float $lat
 * @property float $lng
 * @property string $phone_number
 * @property string $email
 * @property string $whatsapp
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property int $territory_id
 * @property Country $country
 *
 * @package App\Models
 */
class Clinic extends ModelBase
{
    protected $table = 'clinics';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $appends = [
        'name',
        'about',
        'benefits',
        'about_clinic_location',
        'url_image',
        'url_images',
        'territory_id',
    ];

    protected $casts = [
        'id' => 'int',
        'country_id' => 'int',
        'lat' => 'float',
        'lng' => 'float',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name_en',
        'name_pt',
        'about_en',
        'about_pt',
        'benefits_en',
        'benefits_pt',
        'about_clinic_location_en',
        'about_clinic_location_pt',
        'country_id',
        'image',
        'images',
        'address',
        'postcode',
        'lat',
        'lng',
        'phone_number',
        'email',
        'whatsapp',
        'sort',
    ];

    static $publicColumns = [
        'id',
        'name',
        'about',
        'benefits',
        'about_clinic_location',
        'country_id',
        'url_image',
        'url_images',
        'address',
        'postcode',
        'lat',
        'lng',
        'phone_number',
        'email',
        'whatsapp',
        'sort',
        'territory_id',

        'country',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            BlobRepository::filesDelete($model->image, 'image');
            if(!empty($model->images)){
                BlobRepository::filesDelete($model->images, 'image');
            }
        });
    }

    //------------------------------------

    public function getTerritoryIdAttribute()
    {
        return !empty($this->country_id) ? $this->country->territory_id : null;
    }

    public function getNameAttribute()
    {
        return $this->{'name_'.AppConf::$lang};
    }

    public function getAboutAttribute()
    {
        return $this->{'about_'.AppConf::$lang};
    }

    public function getAboutClinicLocationAttribute()
    {
        return $this->{'about_clinic_location_'.AppConf::$lang};
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

    //----------------------------

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

    public function getBenefitsAttribute($value)
    {
        return $this->{'benefits_'.AppConf::$lang};
    }

    public function getBenefitsEnAttribute($value)
    {
        return !empty($value) ? json_decode($value) : [];
    }

    public function setBenefitsEnAttribute($value)
    {
        $value = is_array($value) && !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['benefits_en'] = !empty($value) ? $value : null;
    }

    public function getBenefitsPtAttribute($value)
    {
        return !empty($value) ? json_decode($value) : [];
    }

    public function setBenefitsPtAttribute($value)
    {
        $value = is_array($value) && !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['benefits_pt'] = !empty($value) ? $value : null;
    }


    //---------------------------------------------------------

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}