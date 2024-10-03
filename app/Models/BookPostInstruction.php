<?php

namespace App\Models;

use App\Application;
use App\Repositories\Base\BlobRepository;
use App\Traits\DynamicHiddenVisibleTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Class BookPostInstruction
 *
 * @property int $id
 * @property int $book_id
 * @property string $language_key
 * @property string $title
 * @property string $content
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Book $book
 *
 * @package App\Models
 */
class BookPostInstruction extends ModelBase
{
    protected $table = 'book_post_instructions';

    use DynamicHiddenVisibleTrait;

    public $timestamps = false;

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
        'sort',
    ];

    static $publicColumns = [
        'title',
        'content',
    ];

    protected $attributes = [
        'language_key' => 'en',
        'sort' => 4294967295,
    ];

    //------------------------------------

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}