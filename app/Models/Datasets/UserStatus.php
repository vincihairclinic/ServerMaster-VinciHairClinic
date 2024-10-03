<?php

namespace App\Models\Datasets;

/**
 * Class UserStatus
 */
class UserStatus extends Dataset
{
    const NEW = 0;
    const ACTIVE = 11;
    const INVISIBLE = 21;
    const BLOCKED = 41;

    static $data = [
        [
            'id' => self::NEW,
            'name' => 'New',
            'color' => '#ffc107',
        ],[
            'id' => self::ACTIVE,
            'name' => 'Active',
            'color' => '#8bc34a',
        ],[
            'id' => self::INVISIBLE,
            'name' => 'Invisible',
            'color' => '#ffc107',
        ],[
            'id' => self::BLOCKED,
            'name' => 'Blocked',
            'color' => '#ff4081',
        ]
    ];
}
