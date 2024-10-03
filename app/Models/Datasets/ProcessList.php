<?php

namespace App\Models\Datasets;

use App\AppConf;

/**
 * Class ProcessList
 */
class ProcessList extends Dataset
{
    static function checkEnv($id)
    {
        $settings = ProcessList::findById($id);
        $procEnv = $settings['env'];
        return empty($procEnv) || config('app.env') == $procEnv;
    }

    static $data = [
        [
            'id' => 1,
            'name' => 'Test C1',
            'count' => 1,
            'env' => '',
            'group' => 3,
        ]
    ];
}