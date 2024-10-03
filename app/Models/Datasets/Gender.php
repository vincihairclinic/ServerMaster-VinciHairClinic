<?php

namespace App\Models\Datasets;

/**
 * Class Gender
 */
class Gender extends Dataset
{
    const MALE = 3;
    const FEMALE = 5;
    //const OTHER = 8;

    static $data = [
        [
            'id' => self::MALE,
            'name' => 'Male',
        ],[
            'id' => self::FEMALE,
            'name' => 'Female',
        ]/*,[
            'id' => self::OTHER,
            'name' => 'Other',
        ]*/
    ];
}
