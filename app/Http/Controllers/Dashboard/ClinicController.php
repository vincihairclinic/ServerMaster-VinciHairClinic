<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Clinic\StoreRequest;
use App\Http\Requests\Dashboard\Clinic\UpdateRequest;
use App\Models\Clinic;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ClinicController extends DashboardController
{
    public function index()
    {
        return view('dashboard.clinic.index');
    }

    public function indexJson(Request $request)
    {
        $model = Clinic::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Clinic $model)
    {
        return view('dashboard.clinic.edit', compact('model'));
    }

    public function create()
    {
        $model = new Clinic();
        return view('dashboard.clinic.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Clinic(), $request)) {
            return redirect()->route('dashboard.clinic.index');
        }
        return redirect()->back();
    }

    public function update(Clinic $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.clinic.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Clinic::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Clinic $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.clinic.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Clinic $model, Request $request)
    {
        $request->merge([
            'benefits_en' => empty($request->benefits_en) ? [] : array_values($request->benefits_en),
            'benefits_pt' => empty($request->benefits_pt) ? [] : array_values($request->benefits_pt),
        ]);
        BaseControllerRepository::loadToModelMedia($model, $request);
        BaseControllerRepository::loadToModelMedias($model, $request, 'images', 'image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'about_en',
            'about_pt',
            'benefits_en',
            'benefits_pt',
            'lat',
            'lng',
            'about_clinic_location_en',
            'about_clinic_location_pt',
            'address',
            'postcode',
            'phone_number',
            'email',
            'whatsapp',
            'country_id',
        ], true, true);
        return $result;
    }
}
