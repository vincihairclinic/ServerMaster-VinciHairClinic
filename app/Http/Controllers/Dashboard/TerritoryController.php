<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Territory\StoreRequest;
use App\Http\Requests\Dashboard\Territory\UpdateRequest;
use App\Models\Territory;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class TerritoryController extends DashboardController
{
    public function index()
    {
        return view('dashboard.territory.index');
    }

    public function indexJson(Request $request)
    {
        $model = Territory::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Territory $model)
    {
        return view('dashboard.territory.edit', compact('model'));
    }

    public function create()
    {
        $model = new Territory();
        return view('dashboard.territory.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Territory(), $request)) {
            return redirect()->route('dashboard.territory.index');
        }
        return redirect()->back();
    }

    public function update(Territory $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.territory.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Territory::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

//    public function destroy(Territory $model, Request $request)
//    {
//        if ($model->delete()) {
//            if (!empty($request->ajax)) {
//                return 1;
//            }
//            return redirect()->route('dashboard.territory.index');
//        }
//        if (!empty($request->ajax)) {
//            abort(500);
//        }
//        return redirect()->back();
//    }

    //--------------------------

    public function save(Territory $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request, 'area_image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name',
        ], true, true);
        return $result;
    }
}
