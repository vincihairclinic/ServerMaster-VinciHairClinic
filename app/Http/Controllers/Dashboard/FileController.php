<?php

namespace App\Http\Controllers\Dashboard;

//use App\Http\Requests\Dashboard\Setting \StoreRequest;
use App\AppConf;
use App\Models\File;
use App\Models\Setting ;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;
use Session;

class FileController extends DashboardController
{
    public function edit()
    {
        $model = File::where('name', 'take_a_few_photo_of_your_hair.mp4')->first();
        if (empty($model)) {
            abort(404);
        }
        $model->url_file = route('blob.file', $model->name);
        return view('dashboard.file.edit', compact('model'));
    }

    public function update(Request $request)
    {
        ini_set('max_execution_time', AppConf::$max_execution_time_video);
        ini_set('memory_limit', AppConf::$memory_limit_video);
        $model = File::where('name', 'take_a_few_photo_of_your_hair.mp4')->first();
        if (empty($model)) {
            abort(404);
        }

        $file = $request->file;
        $model->content = file_get_contents($file);
        $model->size = strlen(file_get_contents($file));

        if ($model->save()) {
            Session::put('update_success', 1);
            return  redirect()->back();
        }

        return redirect()->back();
    }

}
