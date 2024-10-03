<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ProcedureResult\StoreRequest;
use App\Http\Requests\Dashboard\ProcedureResult\UpdateRequest;
use App\Models\ProcedureResult;
use App\Repositories\Base\BaseControllerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProcedureResultController extends DashboardController
{
    public function index()
    {
        return view('dashboard.procedure-result.index');
    }

    public function indexJson(Request $request)
    {
        $model = ProcedureResult::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ProcedureResult $model)
    {
        return view('dashboard.procedure-result.edit', compact('model'));
    }

    public function create()
    {
        $model = new ProcedureResult();
        return view('dashboard.procedure-result.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ProcedureResult(), $request)) {
            return redirect()->route('dashboard.procedure-result.index');
        }
        return redirect()->back();
    }

    public function update(ProcedureResult $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.procedure-result.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                ProcedureResult::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ProcedureResult $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.procedure-result.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(ProcedureResult $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'procedure_id',
            'gender_id',
            'date',
            'name_en',
            'name_pt',
            'title_en',
            'title_pt',
            'subtitle_en',
            'subtitle_pt',
            'content_en',
            'content_pt',
        ], true, true);

        $model->tag_procedures()->sync($request->tag_procedures);
        $model->tag_hair_types()->sync($request->tag_hair_types);
        $model->tag_genders()->sync($request->tag_genders);
        return $result;
    }
}
