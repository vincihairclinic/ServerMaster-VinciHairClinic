<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Video;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class VideoController extends DashboardController
{
    public function index()
    {
        return view('dashboard.video.index');
    }

    public function indexJson(Request $request)
    {
        $model = Video::query();
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Video $model)
    {
        return view('dashboard.video.edit', compact('model'));
    }

    public function update(Video $model, Request $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.video.index');
        }
        return redirect()->back();
    }

    public function destroy(Video $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.video.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Video $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
        ], true, true);
        $model->tag_procedures()->sync($request->tag_procedures);
        $model->tag_hair_types()->sync($request->tag_hair_types);
        $model->tag_genders()->sync($request->tag_genders);
        return $result;
    }
}
