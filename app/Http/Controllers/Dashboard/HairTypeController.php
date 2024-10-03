<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\HairType\StoreRequest;
use App\Http\Requests\Dashboard\HairType\UpdateRequest;
use App\Models\HairType;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class HairTypeController extends DashboardController
{
    public function index()
    {
        return view('dashboard.hair-type.index');
    }

    public function indexJson(Request $request)
    {
        $model = HairType::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(HairType $model)
    {
        return view('dashboard.hair-type.edit', compact('model'));
    }

    public function create()
    {
        $model = new HairType();
        return view('dashboard.hair-type.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new HairType(), $request)) {
            return redirect()->route('dashboard.hair-type.index');
        }
        return redirect()->back();
    }

    public function update(HairType $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.hair-type.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                HairType::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(HairType $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.hair-type.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(HairType $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
        ], true, true);
        return $result;
    }
}
