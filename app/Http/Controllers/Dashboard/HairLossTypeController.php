<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\HairLossType\StoreRequest;
use App\Http\Requests\Dashboard\HairLossType\UpdateRequest;
use App\Models\HairLossType;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class HairLossTypeController extends DashboardController
{
    public function index()
    {
        return view('dashboard.hair-loss-type.index');
    }

    public function indexJson(Request $request)
    {
        $model = HairLossType::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(HairLossType $model)
    {
        return view('dashboard.hair-loss-type.edit', compact('model'));
    }

    public function create()
    {
        $model = new HairLossType();
        return view('dashboard.hair-loss-type.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new HairLossType(), $request)) {
            return redirect()->route('dashboard.hair-loss-type.index');
        }
        return redirect()->back();
    }

    public function update(HairLossType $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.hair-loss-type.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                HairLossType::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(HairLossType $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.hair-loss-type.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(HairLossType $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'gender_id',
        ], true, true);
        return $result;
    }
}
