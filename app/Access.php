<?php

namespace App;

use App\Models\Datasets\UserRole;
use App\Models\Datasets\UserStatus;
use Auth;

class Access
{
    static $isAuthBlocked = false;

    static $isAuthGuest = true;
    static $isAuthAdmin = false;
    static $isAuthUser = false;

    static function load()
    {
        if(!Access::$isAuthGuest = empty(Auth::user())){
            if(!Access::$isAuthBlocked = Access::isModelBlocked(Auth::user())){
                Access::$isAuthUser = Access::isModelUser(Auth::user());
                Access::$isAuthAdmin = Access::isModelAdmin(Auth::user());
            }
        }
    }

    //-----------------------------

    static function isModelBlocked($model)
    {
        return $model->status_id == UserStatus::BLOCKED;
    }

    static function isModelAdmin($model)
    {
        return Access::is(UserRole::ADMIN, $model);
    }

    static function isModelUser($model)
    {
        return Access::is(UserRole::USER, $model);
    }

    static function is($roleIds, $model = null)
    {
        $model = $model ? $model : Auth::user();

        if(empty($roleIds) || empty($model)){
            return false;
        }

        $roleIds = !is_array($roleIds) ? [$roleIds] : $roleIds;
        foreach ($roleIds as $roleId){
            if(is_numeric($model)){
                return $model == $roleId;
            }else if(!empty($model->role_id) && $model->role_id == $roleId){
                return true;
            }
        }

        return false;
    }

    //--------------------------------

    static function isSelfModelOrAuthAdmin($model)
    {
        return !empty($model) && (Access::$isAuthAdmin || Access::isSelfModel($model));
    }

    static function isSelfModelOrUser($model)
    {
        return !empty($model) && (Access::$isAuthUser || Access::isSelfModel($model));
    }

    static function isSelfModelOrRole($model, $roles = [])
    {
        $roles = !is_array($roles) ? [$roles] : $roles;
        return !empty($model) && !empty(Auth::user()) && (in_array(Auth::user()->role_id, $roles) || Access::isSelfModel($model));
    }

    static function isSelfModel($model)
    {
        if(empty($model) || empty(Auth::user()) || empty(Auth::user()->id) || Access::$isAuthBlocked){
            return false;
        }

        if(is_numeric($model)){
            return $model == Auth::user()->id;
        }else if(!empty($model->user_id)){
            return $model->user_id == Auth::user()->id;
        }

        return false;
    }

}