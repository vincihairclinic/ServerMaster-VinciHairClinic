<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Procedure\StoreRequest;
use App\Http\Requests\Dashboard\Procedure\UpdateRequest;
use App\Models\Procedure;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProcedureController extends DashboardController
{
    public function index()
    {
        return view('dashboard.procedure.index');
    }

    public function indexJson(Request $request)
    {
        $model = Procedure::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Procedure $model)
    {
        return view('dashboard.procedure.edit', compact('model'));
    }

    public function create()
    {
        $model = new Procedure();
        return view('dashboard.procedure.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Procedure(), $request)) {
            return redirect()->route('dashboard.procedure.index');
        }
        return redirect()->back();
    }

    public function update(Procedure $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.procedure.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Procedure::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Procedure $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.procedure.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Procedure $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
        ], true, true);
        return $result;
    }
}
