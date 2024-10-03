<?php

namespace App\Repositories\Base;

use App\Application;
use App\Models\Setting;
use App\Repositories\BaseRepository;
use DB;

trait ConsoleCommandRepository
{
    static $table = '';
    static $fieldFlag = '';
    static $defaultValueFlag = '';
    static $mysqlConnection = 'mysql';

    static function clear($table = '', $fieldFlag = '', $defaultValueFlag = '')
    {
        $table = !empty($table) ? $table : self::$table;
        $fieldFlag = !empty($fieldFlag) ? $fieldFlag : self::$fieldFlag;
        $defaultValueFlag = !empty($defaultValueFlag) ? $defaultValueFlag : self::$defaultValueFlag;

        $reCall = 0;
        while (true) {
            try{
                $count = DB::connection(self::$mysqlConnection)->table($table)->where($fieldFlag, -Application::$procNum)->count();
                if($count > 0){
                    DB::connection(self::$mysqlConnection)->statement(
                        "UPDATE ".$table." set ".$fieldFlag." = " . $defaultValueFlag . " WHERE ".$fieldFlag." = " . -Application::$procNum." LIMIT ".$count
                    );
                }
                BaseRepository::echoLog("clear ++\n");
                break;
            }catch (\Exception $e){
                if($reCall < 30){
                    $reCall++;
                    BaseRepository::echoLog("ERROR reCall=".$reCall."\n".$e->getMessage()."\n");
                    sleep(rand(2, 10));
                    if(!self::checkFlag(true)) {
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }
        return true;
    }

    static function chargeRows($limit = 20, $orderBy = '', $useIndex = '', $table = '', $fieldFlag = '', $defaultValueFlag = '')
    {
        $table = !empty($table) ? $table : self::$table;
        $fieldFlag = !empty($fieldFlag) ? $fieldFlag : self::$fieldFlag;
        $defaultValueFlag = !empty($defaultValueFlag) ? $defaultValueFlag : self::$defaultValueFlag;

        $orderBy = !empty($orderBy) ? 'order by '.$orderBy : '';
        $reCall = 0;
        while (true) {
            try{
                DB::connection(self::$mysqlConnection)->statement(
                    "UPDATE ".$table." ".$useIndex." set ".$fieldFlag." = ".-Application::$procNum." where ".$fieldFlag." = ".$defaultValueFlag." ".$orderBy." limit ".$limit
                );
                break;
            }catch (\Exception $e){
                if($reCall < 30){
                    $reCall++;
                    BaseRepository::echoLog("ERROR reCall=".$reCall."\n".$e->getMessage()."\n");
                    sleep(rand(2, 10));
                    if(!self::checkFlag(true)) {
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    
    static function checkFlag($withResources = false)
    {
        if(!empty(Application::$procId)){
            if (app()->runningInConsole() && Setting::checkValue(Setting::consoleProcFlag(), 0)) {
                BaseRepository::echoLog(" -- stop\n");
                return false;
            }
            if($withResources){
                return self::checkResources();
            }
        }

        return true;
    }
    
    static function checkResources()
    {
        if(!ServerRepository::checkFreeDiscSpace(2) || !ServerRepository::checkRamDiscSpace()){
            Setting::updateValue(Setting::consoleProcFlag(), 0);
            BaseRepository::echoLog(" -- RESOURCE stop\n");
            return false;
        }
        return true;
    }
}
