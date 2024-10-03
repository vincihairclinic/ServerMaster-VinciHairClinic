<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserLogController extends DashboardController
{
    public function index()
    {
        /*dd(substr(str_replace('App\Http\Controllers\Dashboard', '', \Route::currentRouteAction()), 1), \Route::currentRouteAction());
        dd(preg_replace('#^/#', '', str_replace('App\Http\Controllers\Dashboard', '', \Route::currentRouteAction())));
        dd(preg_replace('#^/#', '', str_replace('App\Http\Controllers\Dashboard', '', \Route::currentRouteAction())));*/
        return view('dashboard.user-log.index');
    }

    public function indexJson(Request $request)
    {
        $model = UserLog::with('user');
        $created_at_from = 10;
        $created_at_to = 11;
        if ($request->columns[$created_at_from]['search']['value'] && $request->columns[$created_at_to]['search']['value']) {
            $model->whereBetween('created_at', [
                Carbon::parse($request->columns[$created_at_from]['search']['value'] . ' 00:00:00')->format('Y-m-d H:i:s'),
                Carbon::parse($request->columns[$created_at_to]['search']['value'] . ' 23:59:59')->format('Y-m-d H:i:s')
            ]);
        } else if ($request->columns[$created_at_from]['search']['value']) {
            $model->where('created_at', '>=', Carbon::parse($request->columns[$created_at_from]['search']['value'] . ' 00:00:00')->format('Y-m-d H:i:s'));
        } else if ($request->columns[$created_at_to]['search']['value']) {
            $model->where('created_at', '<=', Carbon::parse($request->columns[$created_at_to]['search']['value'] . ' 23:59:59')->format('Y-m-d H:i:s'));
        }
        return datatables()->eloquent($model)->toJson();
    }

}
