<?php

namespace App\Http\Controllers\Dashboard;

use App\AppConf;
use App\Http\Requests\Dashboard\ProcedureResultVideo\StoreRequest;
use App\Http\Requests\Dashboard\ProcedureResultVideo\UpdateRequest;
use App\Models\ProcedureResultVideo;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProcedureResultVideoController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson($model, Request $request)
    {
        $model = ProcedureResultVideo::where('procedure_result_id', $model)->orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ProcedureResultVideo $model)
    {
        return $model;
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ProcedureResultVideo(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(ProcedureResultVideo $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                ProcedureResultVideo::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ProcedureResultVideo $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(ProcedureResultVideo $model, Request $request)
    {
        ini_set('max_execution_time', AppConf::$max_execution_time_video);
        ini_set('memory_limit', AppConf::$memory_limit_video);
        BaseControllerRepository::loadToModelMedia($model, $request, 'video', 'video');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'language_key',
            'procedure_result_id',
            'name',
        ], true, true);
        return $result;
    }
}
