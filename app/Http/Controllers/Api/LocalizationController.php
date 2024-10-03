<?php

namespace App\Http\Controllers\Api;

use App\AppConf;
use App\Models\Localization;
use App\Models\Setting;
use Illuminate\Http\Request;

class LocalizationController extends ApiController
{
    public function index(Request $request)
    {
        AppConf::$lang = !empty($request->language_key) ? $request->language_key : 'en';

        Localization::setStaticVisible(Localization::$publicColumns);
        $localizations_table_updated_at = (int)Setting::getValue('localizations_table_updated_at');

        return $this->respondContent([
            'localizations' => !empty($request->last_timestamp) && $request->last_timestamp < $localizations_table_updated_at ? Localization::all() : [],
            'last_timestamp' => $localizations_table_updated_at
        ]);
    }
}
