<?php

namespace App\Models\Datasets;

/**
 * Class Gender
 */
class SettingsLink extends Dataset
{
    const FACEBOOK_URL = 'facebook_url';
    const INSTAGRAM_URL = 'instagram_url';
    const TWITTER_URL = 'twitter_url';

    static $data = [[
            'id' => self::FACEBOOK_URL,
            'name' => 'Facebook',
        ],[
            'id' => self::INSTAGRAM_URL,
            'name' => 'Instagram',
        ],[
            'id' => self::TWITTER_URL,
            'name' => 'Twitter',
        ]
    ];
}
