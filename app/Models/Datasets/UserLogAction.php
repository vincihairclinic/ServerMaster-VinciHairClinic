<?php

namespace App\Models\Datasets;

/**
 * Class UserLogAction
 */
class UserLogAction extends Dataset
{
    const NONE = 0;
    const CREATE = 1;
    const DELETE = 2;
    const UPDATE = 3;

    static $data = [
        [
            'id' => self::NONE,
            'name' => 'NONE',
        ],[
            'id' => self::CREATE,
            'name' => 'CREATE',
        ],[
            'id' => self::DELETE,
            'name' => 'DELETE',
        ],[
            'id' => self::UPDATE,
            'name' => 'UPDATE',
        ],
    ];
}
