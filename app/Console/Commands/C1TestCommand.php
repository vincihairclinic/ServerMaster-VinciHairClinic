<?php

namespace App\Console\Commands;

use App\AppConf;
use App\Application;
use App\Models\Setting;
use App\Repositories\BaseRepository;
use Illuminate\Console\Command;

class C1TestCommand extends Command
{
    protected $signature = 'console-proc-1 {--procNum=} {--sleep=} {--fullUrl=}';

    public function handle()
    {
        AppConf::load($this->option('fullUrl'));

        Application::$procName = 'console_proc';
        Application::$procId = 1;
        Application::$isConsole = true;
        Application::$procNum = $this->option('procNum') ? $this->option('procNum') : 1;

        if(Setting::checkValue(Setting::consoleProcFlag(), 1)) {
            BaseRepository::echoLog(Setting::consoleProcFlag()." is already run");
            return;
        }
        Setting::updateValue(Setting::consoleProcFlag(), 1);
        BaseRepository::echoLog(Setting::consoleProcFlag()." --- start");

        if($this->option('sleep')){
            echo "sleep";
            sleep($this->option('sleep'));
        }

        //---------------------------------------------------------
        

        //---------------------------------------------------------

        Setting::updateValue(Setting::consoleProcFlag(), 0);
        BaseRepository::echoLog(Setting::consoleProcFlag()."\n ----- DONE ---- end");
        return;
    }

}
