<?php

namespace App\Traits;

trait ModelStaticAppendsTrait
{
    static $_appends = [];

    public static function getStaticAppends() {
        return self::$_appends;
    }

    public static function setStaticAppends(array $value) {
        self::$_appends = $value;
        return self::$_appends;
    }

    public static function getDefaultAppends() {
        return with(new static)->getAppends();
    }

    public function getAppends(){
        return $this->appends;
    }

    public function toArray()    {
        if (self::getStaticAppends()) {
            $this->appends = self::getStaticAppends();
        }
        return parent::toArray();
    }

}