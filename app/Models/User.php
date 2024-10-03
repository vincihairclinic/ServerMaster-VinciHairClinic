<?php

namespace App\Models;

use App\Application;
use App\Models\Datasets\UserRole;
use App\Models\Datasets\UserStatus;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property int $role_id
 * @property int $status_id
 * @property string $email
 * @property bool $is_email_verified
 * @property string $password
 * @property string $onesignal_token
 * @property string $version_app
 * @property string $app_state
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * user_personal_details
 * @property int $gender_id
 * @property string $hair_front_image
 * @property string $url_hair_front_image
 * @property string $hair_side_image
 * @property string $url_hair_side_image
 * @property string $hair_back_image
 * @property string $url_hair_back_image
 * @property string $hair_top_image
 * @property string $url_hair_top_image
 * @property string $full_name
 * @property Carbon $date_of_birth
 * @property int $age
 * @property string $phone_number
 * @property int $clinic_id
 * @property int $hair_loss_type_id
 * @property int $hair_type_id
 * @property int $country_id
 * @property string $language_key
 * @property bool $does_your_family_suffer_from_hereditary_hair_loss
 * @property int $how_long_have_you_experienced_hair_loss_for
 * @property bool $would_you_like_to_get_in_touch_with_you
 *
 * user_settings
 * @property bool $is_show_news_and_updates_notification
 * @property bool $is_show_promotions_and_offers_notification
 * @property bool $is_show_insights_and_tips_notification
 * @property bool $is_show_new_articles_notification
 * @property bool $is_show_requests_and_tickets_notification
 *
 * @property bool $is_book_consultation_checked
 * @property bool $is_book_consultation
 * @property bool $is_request_contact_from_this_clinic_checked
 *
 * @property Clinic $clinic
 * @property HairLossType $hair_loss_type
 * @property HairType $hair_type
 * @property Country $country
 * @property Language $language
 *
 * @property Collection $procedures
 * @property Collection $viewed_podcasts
 * @property Collection $book_consultations
 *
 * @package App\Models
 */
class User extends ModelBase implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use DynamicHiddenVisibleTrait;
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasApiTokens;

    protected $table = 'users';

    public $timestamps = false;

    static $hide_auto_email = true;

    protected $appends = [
        'url_hair_front_image',
        'url_hair_side_image',
        'url_hair_back_image',
        'url_hair_top_image',
    ];

    protected $casts = [
        'id' => 'int',
        'role_id' => 'int',
        'status_id' => 'int',
        'is_email_verified' => 'bool',
        //user_personal_details
        'gender_id' => 'int',
        'age' => 'int',
        'clinic_id' => 'int',
        'hair_loss_type_id' => 'int',
        'hair_type_id' => 'int',
        'country_id' => 'int',
        'does_your_family_suffer_from_hereditary_hair_loss' => 'bool',
        'how_long_have_you_experienced_hair_loss_for' => 'int',
        'would_you_like_to_get_in_touch_with_you' => 'bool',
        'is_book_consultation_checked' => 'bool',
        'is_book_consultation' => 'bool',
        'is_request_contact_from_this_clinic_checked' => 'bool',
        //user_settings
        'is_show_news_and_updates_notification' => 'bool',
        'is_show_promotions_and_offers_notification' => 'bool',
        'is_show_insights_and_tips_notification' => 'bool',
        'is_show_new_articles_notification' => 'bool',
        'is_show_requests_and_tickets_notification' => 'bool',
    ];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'role_id',
        'status_id',
        'email',
        'is_email_verified',
        'password',
        'onesignal_token',
        'version_app',
        'app_state',
        'deleted_at',
        'created_at',
        'updated_at',
        //user_personal_details
        'gender_id',
        'hair_front_image',
        'hair_side_image',
        'hair_back_image',
        'hair_top_image',
        'full_name',
        'age',
        'date_of_birth',
        'phone_number',
        'clinic_id',
        'hair_loss_type_id',
        'hair_type_id',
        'country_id',
        'language_key',
        'does_your_family_suffer_from_hereditary_hair_loss',
        'how_long_have_you_experienced_hair_loss_for',
        'would_you_like_to_get_in_touch_with_you',
        'is_book_consultation_checked',
        'is_book_consultation',
        'is_request_contact_from_this_clinic_checked',
        //user_settings
        'is_show_news_and_updates_notification',
        'is_show_promotions_and_offers_notification',
        'is_show_insights_and_tips_notification',
        'is_show_new_articles_notification',
        'is_show_requests_and_tickets_notification',
    ];

    static $publicColumns = [
        'id',
        //user_personal_details
        'full_name',
    ];

    static $publicSelfColumns = [
        'id',
        'email',
        'is_email_verified',
        'app_state',
        //user_personal_details
        'gender_id',
        'url_hair_front_image',
        'url_hair_side_image',
        'url_hair_back_image',
        'url_hair_top_image',
        'full_name',
        'date_of_birth',
        'age',
        'phone_number',
        'procedures',
        'clinic_id',
        'clinic',
        'hair_loss_type_id',
        'hair_type_id',
        'hair_loss_type',
        'hair_type',
        'country_id',
        'country',
        'language_key',
        'language',
        'does_your_family_suffer_from_hereditary_hair_loss',
        'how_long_have_you_experienced_hair_loss_for',
        'would_you_like_to_get_in_touch_with_you',
        //user_settings
        'is_show_news_and_updates_notification',
        'is_show_promotions_and_offers_notification',
        'is_show_insights_and_tips_notification',
        'is_show_new_articles_notification',
        'is_show_requests_and_tickets_notification',
    ];

    protected $attributes = [
        'role_id'   => UserRole::USER,
        'status_id' => UserStatus::ACTIVE,
        'is_email_verified' => 0,
        //user_personal_details
        'language_key' => 'en',
        //user_settings
        'is_show_news_and_updates_notification' => 1,
        'is_show_promotions_and_offers_notification' => 1,
        'is_show_insights_and_tips_notification' => 1,
        'is_show_new_articles_notification' => 0,
        'is_show_requests_and_tickets_notification' => 1,
        'is_book_consultation_checked' => 0,
        'is_book_consultation' => 0,
        'is_request_contact_from_this_clinic_checked' => 0,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function(self $model) {
            /*if(!empty($model->hair_front_image)){
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
            }*/
        });
    }

    //---------------------------------------------------------

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['age'] = !empty($value) ? Carbon::parse($value)->diff(Carbon::now())->y : 0;
        $this->attributes['date_of_birth'] = $value;
    }

    //------------------------------------

    public function getEmailAttribute($value)
    {
        if (self::$hide_auto_email && preg_match('/^_t_m_p_/', $value)) {
            return '';
        }
        return $value;
    }

    //------------------------------------

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
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
                //BlobRepository::filesDelete($this->getOriginal('hair_front_image'), 'image');
            }
        }

        $this->attributes['hair_front_image'] = $value;
    }

    public function setHairFrontImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_front_image') != $value) {
                //BlobRepository::filesDelete($this->getOriginal('hair_front_image'), 'image');
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
                //BlobRepository::filesDelete($this->getOriginal('hair_side_image'), 'image');
            }
        }

        $this->attributes['hair_side_image'] = $value;
    }

    public function setHairSideImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_side_image') != $value) {
                //BlobRepository::filesDelete($this->getOriginal('hair_side_image'), 'image');
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
                //BlobRepository::filesDelete($this->getOriginal('hair_back_image'), 'image');
            }
        }

        $this->attributes['hair_back_image'] = $value;
    }

    public function setHairBackImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_back_image') != $value) {
                //BlobRepository::filesDelete($this->getOriginal('hair_back_image'), 'image');
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
                //BlobRepository::filesDelete($this->getOriginal('hair_top_image'), 'image');
            }
        }

        $this->attributes['hair_top_image'] = $value;
    }

    public function setHairTopImageAttribute($value)
    {
        if(!empty($this->id)) {
            if ($this->getOriginal('hair_top_image') != $value) {
                //BlobRepository::filesDelete($this->getOriginal('hair_top_image'), 'image');
            }
        }

        $this->attributes['hair_top_image'] = $value;
    }

    //---------------------------------------------------------

    public function procedures()
    {
        return $this->belongsToMany(Procedure::class, 'user_procedure');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function hair_loss_type()
    {
        return $this->belongsTo(HairLossType::class);
    }

    public function hair_type()
    {
        return $this->belongsTo(HairType::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function viewed_podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'user_viewed_podcasts');
    }

    public function book_consultations()
    {
        return $this->hasMany(BookConsultation::class);
    }

}
