<?php

namespace App\Http\Controllers\Dashboard;

//use App\Http\Requests\Dashboard\Setting \StoreRequest;
use App\Models\Setting ;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;
use Session;

class SettingsLinkController extends DashboardController
{
    public function edit($model)
    {
        $model = Setting::where('id', $model)->first();
        if (empty($model)) {
            abort(404);
        }
        return view('dashboard.settings-link.edit', compact('model'));
    }

    public function update(Request $request, $model)
    {
        $model = Setting::where('id', $model)->first();
        if (empty($model)) {
            abort(404);
        }

        if ($model->description == 'url') {
            if (!filter_var($request->value, FILTER_VALIDATE_URL)) {
                $request->merge(['value' => 'https://'.$request->value]);
            }
        }

        if (BaseControllerRepository::loadToModelBase($model, $request, [
            'value',
        ], true, true)) {
            Session::put('update_success', 1);
            return  redirect()->back();
        }

        return redirect()->back();
    }

}
