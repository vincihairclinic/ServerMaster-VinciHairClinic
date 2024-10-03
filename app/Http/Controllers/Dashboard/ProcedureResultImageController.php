<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ProcedureResultImage\StoreRequest;
use App\Http\Requests\Dashboard\ProcedureResultImage\UpdateRequest;
use App\Models\ProcedureResultImage;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProcedureResultImageController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson($model, Request $request)
    {
        $model = ProcedureResultImage::where('procedure_result_id', $model)->orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ProcedureResultImage $model)
    {
        return $model;
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ProcedureResultImage(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(ProcedureResultImage $model, UpdateRequest $request)
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
                ProcedureResultImage::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ProcedureResultImage $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(ProcedureResultImage $model, Request $request)
    {
        BaseControllerRepository::loadToModelMedia($model, $request, 'befor_image');
        BaseControllerRepository::loadToModelMedia($model, $request, 'after_image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'procedure_result_id',
        ], true, true);
        return $result;
    }
}
