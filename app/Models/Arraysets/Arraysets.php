<?php

namespace App\Models\Arraysets;

/**
 * Class Arraysets
 */
class Arraysets
{
    static function getRandom()
    {
        return static::$array[rand(0, count(static::$array)-1)];
    }

    static function implodeIds($separator = ',')
    {
        return implode($separator, static::$array);
    }
}
