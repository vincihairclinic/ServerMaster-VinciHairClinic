<?php

namespace App\Repositories\Base;

use App\Repositories\BaseRepository;
use Exception;

class ServerRepository
{
    static function parseDiscSpace($point = '/dev/sda1')
    {
        if($sda1 = shell_exec('df '.$point.' -h')){
            $sda1 = ParseRepository::explode($point, 1, $sda1);
            if(!empty($sda1)){
                $sda1 = explode(' ', $sda1);
                $sda1 = array_values(array_filter($sda1));
                if(!empty($sda1[0]) && !empty($sda1[2])){
                    return str_replace('G', '', $sda1[2].' ('.$sda1[0].')');
                }
            }
        }
    }

    // MB
    static function checkRamDiscSpace($min = 500)
    {
        try {
            $fh = fopen('/proc/meminfo','r');
            $mem = 0;
            while ($line = fgets($fh)) {
                $pieces = array();
                if (preg_match('/^MemAvailable:\s+(\d+)\skB$/', $line, $pieces)) {
                    $mem += (float)$pieces[1];
                }
                if (preg_match('/^SwapFree:\s+(\d+)\skB$/', $line, $pieces)) {
                    $mem += (float)$pieces[1];
                }
            }
            fclose($fh);

            //MB /1000
            //GB /1000000

            if($min > ceil($mem/1000)){
                BaseRepository::echoLog("\n check RAM !!!!!\n");
                return false;
            }
        }catch (Exception $e){}

        return true;
    }

    static function parseRamSpace()
    {
        $fh = fopen('/proc/meminfo','r');
        $mem = 0;
        $totalMem = 0;
        while ($line = fgets($fh)) {
            $pieces = array();
            if (preg_match('/^MemAvailable:\s+(\d+)\skB$/', $line, $pieces)) {
                $mem += (float)$pieces[1];
            }
            if (preg_match('/^SwapFree:\s+(\d+)\skB$/', $line, $pieces)) {
                $mem += (float)$pieces[1];
            }
            if (preg_match('/^SwapTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $totalMem += (float)$pieces[1];
            }
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $totalMem += (float)$pieces[1];
            }
        }
        fclose($fh);

        //MB /1000
        //GB /1000000
        return round($mem/1000000, 1).' ('.round($totalMem/1000000, 1).')';
    }

    static function checkFreeDiscSpace($min = 1)
    {

        /*if(in_array(config('app.name'), ['rno.pp.ua', 'traveled.pp.ua'])){
            return true;
        }*/

        try {
            if($sda1 = shell_exec('df /dev/sda1 -h')){
                $sda1 = ParseRepository::explode('/dev/sda1', 1, $sda1);
                if(!empty($sda1)){
                    $sda1 = explode(' ', $sda1);
                    $sda1 = array_values(array_filter($sda1));
                    if(!empty($sda1[2])){
                        if((float)$sda1[2] <= $min){
                            BaseRepository::echoLog("\n checkFreeDiscSpace !!!!!\n");
                            return false;
                        }
                    }
                }
            }

            if($sda1 = shell_exec('df /dev/sdb -h')){
                $sda1 = ParseRepository::explode('/dev/sdb', 1, $sda1);
                if(!empty($sda1)){
                    $sda1 = explode(' ', $sda1);
                    $sda1 = array_values(array_filter($sda1));
                    if(!empty($sda1[2])){
                        if((float)$sda1[2] <= $min){
                            BaseRepository::echoLog("\n checkFreeDiscSpace !!!!!\n");
                            return false;
                        }
                    }
                }
            }
        }catch (Exception $e){}


        return true;
    }
}