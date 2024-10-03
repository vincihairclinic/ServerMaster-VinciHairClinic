<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;

/**
 * Class BookPreAdditionalItem
 *
 * @property int $id
 * @property int $book_pre_additional_id
 * @property string $title
 * @property string $content
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Book $book_pre_additional
 *
 * @package App\Models
 */
class BookPreAdditionalItem extends ModelBase
{
    protected $table = 'book_pre_additional_items';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'book_pre_additional_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'book_pre_additional_id',
        'title',
        'content',
        'sort',
    ];

    static $publicColumns = [
        'title',
        'content',
    ];

    protected $attributes = [
        'sort' => 4294967295,
    ];

    //------------------------------------

    public function book_pre_additional()
    {
        return $this->belongsTo(BookPreAdditional::class);
    }
}