<?php

namespace App\Models;

use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Video
 *
 * @property int $id
 * @property string $title
 * @property string $source
 * @property bool $is_live_stream
 * @property Carbon $scheduled_at
 * @property Carbon $published_at
 * @property Carbon $created_at
 *
 * @property string $url_source
 * @property string $author_name
 * @property string $url_author_image
 *
 * @property Collection $tags
 *
 * @property Collection $tag_procedures
 * @property Collection $tag_hair_types
 * @property Collection $tag_genders
 *
 * @package App\Models
 */
class Video extends ModelBase
{
    use DynamicHiddenVisibleTrait;

    protected $table = 'videos';

    public $timestamps = false;

    protected $appends = [
        'url_source',
        'url_preview',
        'author_name',
        'url_author_image',
    ];

    protected $casts = [
        'id' => 'int',
        'is_live_stream' => 'int',
    ];

    protected $fillable = [
        'title',
        'source',
        'is_live_stream',
        'scheduled_at',
        'published_at',
    ];

    static $publicColumns = [
        'id',
        'title',
        'published_at',
        'url_source',
        'url_preview',
        'author_name',
        'url_author_image',
    ];


    protected $attributes = [
        'is_live_stream' => 0,
    ];

    //------------------------------------

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag');
    }

    //------------------------------------

    public function getUrlSourceAttribute()
    {
        return !empty($this->source) ? 'https://www.youtube.com/watch?v='.$this->source : null;
    }

    public function getUrlPreviewAttribute()
    {
        return !empty($this->source) ? 'https://img.youtube.com/vi/'.$this->source.'/sddefault.jpg' : asset('/images/base/upload-image-default.png');
    }

    //------------------------------------

    public function getAuthorNameAttribute($value)
    {
        return Setting::getValue('youtube_channel_name');
    }

    public function getUrlAuthorImageAttribute($value)
    {
        return Setting::getValue('url_youtube_channel_image');
    }

    //------------------------------------

    public function tag_procedures()
    {
        return $this->belongsToMany(Procedure::class, 'tag_video_procedures');
    }

    public function tag_hair_types()
    {
        return $this->belongsToMany(HairType::class, 'tag_video_hair_types');
    }

    public function tag_genders()
    {
        return $this->belongsToMany(Gender::class, 'tag_video_genders');
    }
}
