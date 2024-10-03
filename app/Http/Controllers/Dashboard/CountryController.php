<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Country\StoreRequest;
use App\Http\Requests\Dashboard\Country\UpdateRequest;
use App\Models\Country;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class CountryController extends DashboardController
{
    public function index()
    {
        return view('dashboard.country.index');
    }

    public function indexJson(Request $request)
    {
        $model = Country::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Country $model)
    {
        return view('dashboard.country.edit', compact('model'));
    }

    public function create()
    {
        $model = new Country();
        return view('dashboard.country.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Country(), $request)) {
            return redirect()->route('dashboard.country.index');
        }
        return redirect()->back();
    }

    public function update(Country $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.country.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Country::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

//    public function destroy(Country $model, Request $request)
//    {
//        if ($model->delete()) {
//            if (!empty($request->ajax)) {
//                return 1;
//            }
//            return redirect()->route('dashboard.country.index');
//        }
//        if (!empty($request->ajax)) {
//            abort(500);
//        }
//        return redirect()->back();
//    }

    //--------------------------

    public function save(Country $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request, 'flag_image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name',
            'phone_code',
            'territory_id',
            'host',
            'shop_url',
        ], true, true);
        return $result;
    }
}
