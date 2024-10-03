<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '5000M');
    }

    public function index()
    {

        Setting::loadAll();
        //dd(md5(123456));
        return view('dashboard.admin.index');
    }

    public function settingUpdate(Request $request)
    {
        Setting::updateValue('lock_swipe_message', !empty($request->lock_swipe_message) ? $request->lock_swipe_message : null);
        Setting::updateValue('is_lock_swipe', !empty($request->is_lock_swipe) ? $request->is_lock_swipe : null);
        return redirect()->back();
    }

    public function clearView()
    {
        shell_exec('php '.base_path().'/artisan view:clear');
        shell_exec('php '.base_path().'/artisan clear-compiled');
        shell_exec('php '.base_path().'/artisan config:clear');

        return 1;
    }

    //--------------------

    function updateSetting(Request $request, Setting $model = null)
    {
        if (empty($model)){
            abort(404);
        }
        if($model->update([
            'value' => $request->value,
        ])) {
            return response()->json('', Response::HTTP_OK, []);
        }
        return response()->json('', Response::HTTP_NOT_FOUND, []);
    }


}
