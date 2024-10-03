<?php

namespace App\Models;

use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Support\Carbon;

/**
 * Class UserViewedPodcast
 *
 * @property int $id
 * @property int $user_id
 * @property int $podcast_id
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class UserViewedPodcast extends ModelBase
{
    protected $table = 'user_viewed_podcasts';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'podcast_id' => 'int',
    ];

    protected $fillable = [
        'user_id',
        'podcast_id',
    ];

    static $publicColumns = [
        'id',
        'user_id',
        'podcast_id',
        'created_at',
    ];

    //---------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

}