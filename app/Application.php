<?php

namespace App;

use App\Http\Controllers\Web\WebController;
use App\Models\Arraysets\Proxy;
use Illuminate\Support\Str;

class Application extends \Illuminate\Foundation\Application
{
    static $procName = 'console_proc';
    static $procId = 0;
    static $procNum = 0;
    static $isConsole = false;
    static $isError = false;
    static $isTest = false;

    static $settings = null;
    static $maxPoints = 250;
    static $maxSumPoints = 750;



    static $reservedWords = ['category', 'section', 'page', 'about', 'contact-us', 'contact', 'privacy', 'api', 'terms', 'web', 'help', 'faq', 'dashboard', 'login', 'auth', 'logout', 'register', 'profile', 'info', 'user', 'users', 'default', 'select2', 'test', 'product', 'products', 'search', 'shopping-cart', 'delivery', 'payment', 'how-make-order', 'return-order', 'check', 'confirmation', 'password', 'redirect', 'editor', 'edit', 'store', 'update', 'ajax', 'delete', 'remove', 'offer', 'offers', 'count', 'list', 'sitemap', 'filter', 'article', 'articles', 'captcha', 'car', 'brand', 'cars', 'brands', 'car-brand', 'car-brands', 'model'];

    static $pushLoaded = [];
    static function pushLoad($name)
    {
        if(!in_array($name, self::$pushLoaded)){
            self::$pushLoaded[] = $name;
            return true;
        }
        return false;
    }

    static function getProxy()
    {
        return false;
        return (object)Proxy::$array[rand(0, count(Proxy::$array)-1)];
    }

    static $descriptionServer = null;
    static function getDescriptionServer()
    {
        return !empty(Application::$descriptionServer) ? Application::$descriptionServer : config('app.name');
    }

    static function storageImageAsset($filePath = '')
    {
        return Application::storageAsset($filePath, 'image');
    }

    static function blobFileAsset($image)
    {
        return route('blob.file', $image);
    }

    static function storageFileAsset($filePath = '')
    {
        return Application::storageAsset($filePath, 'file');
    }

    static function storageAsset($filePath = '', $type = 'image')
    {
        if(preg_match('/^http(s)?:\/\/(.+)?$/', $filePath)){
            return $filePath;
        }
        return (!empty(AppConf::$storage_url) ? AppConf::$storage_url : config('app.url')).'/storage/'.$type.'/'.$filePath;
    }

    //--------------------------------------




}