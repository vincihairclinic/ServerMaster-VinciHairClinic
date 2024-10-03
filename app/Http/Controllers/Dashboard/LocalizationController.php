<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Localization\StoreRequest;
use App\Http\Requests\Dashboard\Localization\UpdateRequest;
use App\Models\Localization;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;
use App\Models\Setting;
use Carbon\Carbon;

class LocalizationController extends DashboardController
{
    public function index()
    {
        return view('dashboard.localization.index');
    }

    public function indexJson(Request $request)
    {
        $model = Localization::query();
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Localization $model)
    {
        return view('dashboard.localization.edit', compact('model'));
    }

    public function create()
    {
        $model = new Localization();
        return view('dashboard.localization.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Localization(), $request)) {
            return redirect()->route('dashboard.localization.index');
        }
        return redirect()->back();
    }

    public function update(Localization $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.localization.index');
        }
        return redirect()->back();
    }

    public function destroy(Localization $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.localization.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Localization $model, Request $request)
    {
        Setting::updateValue('localizations_table_updated_at', Carbon::now()->timestamp);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'value_en',
            'value_pt',
            'key',
        ], true, true);
        return $result;
    }
}
