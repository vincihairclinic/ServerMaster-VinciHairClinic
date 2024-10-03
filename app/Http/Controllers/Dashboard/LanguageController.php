<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Language\StoreRequest;
use App\Http\Requests\Dashboard\Language\UpdateRequest;
use App\Models\Language;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class LanguageController extends DashboardController
{
    public function index()
    {
        return view('dashboard.language.index');
    }

    public function indexJson(Request $request)
    {
        $model = Language::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Language $model)
    {
        return view('dashboard.language.edit', compact('model'));
    }

    public function create()
    {
        $model = new Language();
        return view('dashboard.language.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Language(), $request)) {
            return redirect()->route('dashboard.language.index');
        }
        return redirect()->back();
    }

    public function update(Language $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.language.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Language::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Language $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.language.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Language $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'key',
            'name',
        ], true, true);
        return $result;
    }
}
