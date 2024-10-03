<?php

namespace App\Models\Datasets;

/**
 * Class UserRole
 */
class UserRole extends Dataset
{
    const USER = 11;
    const ADMIN = 91;

    static $data = [
        [
            'id'   => self::USER,
            'name' => 'User',
        ],[
            'id'   => self::ADMIN,
            'name' => 'Admin',
        ]
    ];
}
