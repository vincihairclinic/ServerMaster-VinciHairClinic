<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\UserSimulationRequest\StoreRequest;
use App\Http\Requests\Dashboard\UserSimulationRequest\UpdateRequest;
use App\Models\UserSimulationRequest;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class UserSimulationRequestController extends DashboardController
{
    public function index()
    {
        return view('dashboard.user-simulation-request.index');
    }

    public function indexJson(Request $request)
    {
        $model = UserSimulationRequest::with('user');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(UserSimulationRequest $model)
    {
        return view('dashboard.user-simulation-request.edit', compact('model'));
    }

    public function create()
    {
        $model = new UserSimulationRequest();
        return view('dashboard.user-simulation-request.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new UserSimulationRequest(), $request)) {
            return redirect()->route('dashboard.user-simulation-request.index');
        }
        return redirect()->back();
    }

    public function update(UserSimulationRequest $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.user-simulation-request.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                UserSimulationRequest::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(UserSimulationRequest $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.user-simulation-request.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(UserSimulationRequest $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_front_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_side_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_back_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_top_image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'user_id',
            'email',
            'full_name',
            'phone_number',
            'country_id',
        ], true, true);
        return $result;
    }
}
