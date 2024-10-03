<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\SimulationRequestType\StoreRequest;
use App\Http\Requests\Dashboard\SimulationRequestType\UpdateRequest;
use App\Models\SimulationRequestType;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class SimulationRequestTypeController extends DashboardController
{
    public function index()
    {
        return view('dashboard.simulation-request-type.index');
    }

    public function indexJson(Request $request)
    {
        $model = SimulationRequestType::orderBy('sort');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(SimulationRequestType $model)
    {
        return view('dashboard.simulation-request-type.edit', compact('model'));
    }

    public function create()
    {
        $model = new SimulationRequestType();
        return view('dashboard.simulation-request-type.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new SimulationRequestType(), $request)) {
            return redirect()->route('dashboard.simulation-request-type.index');
        }
        return redirect()->back();
    }

    public function update(SimulationRequestType $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.simulation-request-type.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                SimulationRequestType::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(SimulationRequestType $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.simulation-request-type.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(SimulationRequestType $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
        ], true, true);
        return $result;
    }
}
