<?php

namespace App;

use Config;

class AppConf
{
    static $check_domain_enable = true;
    static $is_dev_mode = true;

    static $server_name = 'Vinci Hair Clinic';
    static $url_id = '';
    static $storage_url = null;
    static $memory_limit_video = '3072M';
    static $max_execution_time_video = 240;

    static $lang = 'en';
    static $country = 'UK';
    static $locale = 'en';

    static $site_name = 'Vinci Hair Clinic';
    static $site_name_full = 'Vinci Hair Clinic';
    static $site_description = '';

    static $id_yandex_verification = false;
    static $id_mailru_verification = false;
    static $id_rambler = false;
    static $id_yandex_ads_mobile = false;
    static $id_yandex_ads_amp = false;
    static $id_google_verification = false;
    static $id_google_analytics = false;
    static $id_google_ads = false;
    static $is_adsense = false;
    static $is_yandex_ads = false;
    static $id_adwords = false;
    static $id_adwords_add_product = false;

    static $captcha_key = false;
    static $captcha_secret = false;
    static $firebase_auth_key = false;

    static $disableRegistration = true;

    static $width_content = [960, 790, 620]; //369
    static $width_menu = [320, 220];
    static $save_image_max_height = 1024;
    static $save_image_max_width = 1024;
    static $save_image_compression = 80;

    static $defaultConfig = -1;
    static $enableShop = false;
    static $impresjaReal = false;

    static $site_email = '';
    static $phone = '';
    static $address = '';

    static $enableMDLStyle = false;
    static $is_tmpl_exist = false;

    static $disableRemoveImage = false;


    static function load($fullUrl = null)
    {
        if(!app()->runningInConsole() && empty($fullUrl)){
            $fullUrl = request()->fullUrl();
        }

        if(!empty($fullUrl)){
            $requestPath = parse_url($fullUrl);
            if(empty($requestPath['host']) || empty($requestPath['scheme'])){
                abort(404);
            }

            $host = $requestPath['host'];
            if(strpos($host, 'amp.') !== false){
                $host = str_replace('amp.', '', $host);
            }
            $url = $requestPath['scheme'].'://'.$host;

            Config::set('app.name', $host);
            Config::set('app.host', $host);
            Config::set('app.url', $url);
            if(app()->runningInConsole()){
                url()->forceRootUrl($url);
            }

            if(in_array($url, ['https://uk.vinci-hair-clinic-v2.kodetechnologies.com', 'https://dashboard.uk.vinci-hair-clinic-v2.kodetechnologies.com', 'https://api.uk.vinci-hair-clinic-v2.kodetechnologies.com'])){
                Config::set('database.connections.mysql.read', config('database.connections.mysql.write'));

            }else if(in_array($url, ['https://br.vinci-hair-clinic-v2.kodetechnologies.com', 'https://dashboard.br.vinci-hair-clinic-v2.kodetechnologies.com', 'https://api.br.vinci-hair-clinic-v2.kodetechnologies.com'])){

            }else {
                abort(404);
            }

            AppConf::$firebase_auth_key = env('FIREBASE_AUTH_KEY');
            Config::set('app.locale', (!empty(AppConf::$locale) ? AppConf::$locale : (AppConf::$lang != AppConf::$country ? AppConf::$lang.'-'.mb_strtoupper(AppConf::$country) : AppConf::$lang)));

            //---------------------------------------------------------

            if(!config('app.debug')){
                \Debugbar::disable();
            }
        }
    }
}