<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Datasets\ProcessList;
use App\Models\Setting;
use App\Repositories\Base\ServerRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ConsoleProcController extends DashboardController
{
    public function index($procId = 1, $procNum = 1)
    {
        if($setting = Setting::where('id', Setting::consoleProcFlag($procId, $procNum))->first()){
            $lastUpdate = Carbon::parse($setting->updated_at)->format('d.m.Y H:i');
        }else{
            $lastUpdate = Carbon::now()->format('d.m.Y H:i');
        }

        if(!ProcessList::checkEnv($procId)){
            return redirect()->route('dashboard.admin.index');
        }

        if($processList = ProcessList::findById($procId)){
            $countProc = $processList['count'];
            $infoProc = $processList['name'];
            $note = !empty($processList['note']) ? $processList['note'] : '';
        }else{
            return redirect()->route('dashboard.admin.index');
        }

        $loadavg = '<b class="color-gray">&nbsp; L <b class="color-red1">'.sys_getloadavg()[0].' </b> &nbsp; M <b class="color-green1">'.ServerRepository::parseRamSpace().' </b> &nbsp; D <b class="color-blue">'.ServerRepository::parseDiscSpace().'</b> &nbsp; D <b class="color-blue1">'.ServerRepository::parseDiscSpace('/dev/sdb').'</b></b>';

        return view('dashboard.console-proc.index', compact('procId', 'procNum', 'lastUpdate', 'countProc', 'infoProc', 'note', 'loadavg', 'processList'));
    }

    public function resetLastId($procId, $procNum)
    {
        Setting::updateValue(Setting::consoleProcLastId($procId, $procNum), 0);
        return 1;
    }

    public function totalResult($procId, $procNum)
    {
        $logFilePath = self::logFilePath($procId, $procNum);

        $tail = shell_exec('tail -n 30 '.$logFilePath);
        $filesize = '';
        //$filesize = filesize($logFilePath);

        return [
            'flag' => (int)Setting::getValue(Setting::consoleProcFlag($procId, $procNum)),
            'loadavg' => implode(' | ', sys_getloadavg()).'<br>'.ServerRepository::parseRamSpace().'<br>'.ServerRepository::parseDiscSpace().' -- '.ServerRepository::parseDiscSpace('/dev/sdb'),
            'logtail' => str_replace(PHP_EOL, '<br>', Str::limit(strip_tags($tail), 100000)),
        ];
    }

    public function start($procId, $procNum)
    {
        if(Setting::checkValue(Setting::consoleProcFlag($procId, $procNum), 1)){
            return 0;
        }

        $logFilePath = self::logFilePath($procId, $procNum);
        if(empty(request()->sleep)){
            file_put_contents($logFilePath, '');
        }

        shell_exec('php '.base_path().'/artisan console-proc-'.$procId.(!empty(request()->sleep) ? ' --sleep=30' : '').' --procNum='.$procNum.' --fullUrl='.request()->fullUrl().' >> '.$logFilePath.' &');

        return 1;
    }

    public function stop($procId, $procNum)
    {
        Setting::updateValue(Setting::consoleProcFlag($procId, $procNum), 0);
        return 1;
    }

    public function clearLog($procId, $procNum)
    {
        $logFilePath = self::logFilePath($procId, $procNum);
        file_put_contents($logFilePath, '');
        return 1;
    }

    //-------------------------------------------

    static function logFilePath($procId, $procNum)
    {
        $logFilePath = base_path().'/storage/logs/console_proc_'.$procId.'_'.$procNum.'_'.config('app.host').'.log';
        if(!file_exists($logFilePath)){
            file_put_contents($logFilePath, '');
            chmod($logFilePath, 0777);
        }
        return $logFilePath;
    }
}
