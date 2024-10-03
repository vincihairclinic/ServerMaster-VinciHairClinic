<?php

namespace App\Http\Controllers\Web;

use App;
use App\W;
use App\Models\File;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends WebController
{
    public function index()
    {
        W::$pageType = 'index';

        self::_loadMeta();
        self::_loadBackUrl();

        return view('web.index');
    }

    public function blobFile($name, Request $request)
    {
        $start_time = microtime(true);
        ini_set('max_execution_time', 240);
        ini_set('memory_limit', '4096M');
        \Debugbar::disable();
        if (empty($model = File::where('name', $name)->select('size')->first())) {
            abort(404);
        }

        $size = $model->size;
        $start = 0;
        $end = $size - 1;
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = explode('=', $_SERVER['HTTP_RANGE'], 2)[1];
            $end = $size - 1;
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes -1--1/0");
                exit;
            }

            if ($range == '-') {
                $start = $size - substr($range, 1);
            } else{
                $range = explode('-', $range);
                $start = $range[0];
                $end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $end;
            }
            $end = ($end > ($size - 1)) ? ($size - 1) : $end;
            if ($start > $end || $start > $size - 1 || $end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes -1--1/0");
                exit;
            }
        }

        $range_size = $end - $start;

        if ($range_size > 2000000) {
            $end = $start + 2000000;
            $end = $end >= $size ? $size - 1 : $end;
        }

//        $end_time = microtime(true);
//        $qwe = DB::select(DB::raw('select content from `files` where `name` = "'.$name.'"'));
//        $full_time = microtime(true);
//        dd($start_time, $end_time, $full_time, $end_time - $start_time, $full_time - $end_time, $full_time - $start_time);

        $model = File::where('name', $name)->selectRaw('mid(content,'.($start + 1).','.($end - $start + 1).') as part')->first();
//        $full_time = microtime(true);
//        dd($start_time, $end_time, $full_time, $end_time - $start_time, $full_time - $end_time, $full_time - $start_time);
        return response($model->part, Response::HTTP_PARTIAL_CONTENT)
            ->header('Content-Type', 'multipart/byteranges')
            ->header('Content-Length', ($end - $start) + 1)
            ->header('Content-Range', 'bytes '.$start.'-'.$end.'/'.$size);
//        $model = File::where('name', $name)->first();
//        return response($model->content)
//            ->header('Cache-Control', 'no-cache, public')
//            ->header('Content-Description', 'File Transfer')
//            ->header('Content-Type', $model->mime_type)
//            ->header('Content-length', $model->size)
//            ->header('Content-Transfer-Encoding', 'binary');

    }

    public function qrCode($code, $size = 1000)
    {
        \Debugbar::disable();
        return \QrCode::format('png')->size($size)->generate($code);
    }

    public function qrCodeImage($code, $size = 1000, $ext = null)
    {
        \Debugbar::disable();
        return \QrCode::encoding('UTF-8')->size($size)->generate($code);
    }
}
