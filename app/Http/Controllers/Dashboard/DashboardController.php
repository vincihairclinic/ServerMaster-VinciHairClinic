<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

    }

    static function datatablesRemoveColumns(&$request, $columnIds = [])
    {
        foreach ($columnIds as $columnId){
            $requestColumns = $request->columns;
            $requestColumns[$columnId]['search']['value'] = null;
            $request->merge([
                'columns' => $requestColumns
            ]);
        }
    }

    static function responseAjax($html, $disableRemoveSpace = false)
    {
        if(!$disableRemoveSpace){
            $html = str_replace("\r", '', $html);
            $html = str_replace("\n", '', $html);
            $html = preg_replace("/\s\s+/", ' ', $html);
        }

        return response(json_encode([
            'html' => $html
        ], JSON_UNESCAPED_UNICODE), 200)->header('Content-Type', 'application/json');
    }
}
