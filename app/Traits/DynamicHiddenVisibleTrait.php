<?php

namespace App\Traits;

trait DynamicHiddenVisibleTrait
{
    public static $_hidden = null;
    public static $_visible = null;
    public static $_append = null;

    public static function setStaticHidden(array $value) {
        self::$_hidden = $value;
        return self::$_hidden;
    }

    public static function getStaticHidden() {
        return self::$_hidden;
    }

    public static function setStaticVisible(array $value) {
        self::$_visible = $value;
        return self::$_visible;
    }

    public static function getStaticVisible() {
        return self::$_visible;
    }

    public static function setStaticAppend(array $value) {
        self::$_append = $value;
        return self::$_append;
    }

    public static function getStaticAppend() {
        return self::$_append;
    }

    public static function getDefaultHidden() {
        return with(new static)->getHidden();
    }

    public static function geDefaultVisible() {
        return with(new static)->getVisible();
    }

    public static function geDefaultAppend() {
        return with(new static)->getAppend();
    }


    public function toArray()    {
        if (self::getStaticVisible())
            $this->visible = self::getStaticVisible();
        else if (self::getStaticHidden())
            $this->hidden = self::getStaticHidden();
       if (self::getStaticAppend())
            $this->appends = self::getStaticAppend();
        return parent::toArray();
    }

}