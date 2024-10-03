<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Datasets\ListModels;
use App\Models\UserLog;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ListModelController extends DashboardController
{
    public function index($settingsListModels)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];

        return view('dashboard.list-models.index', compact('settingsListModels'));
    }

    public function indexJson(Request $request, $settingsListModels)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];
        $controller = $settingsListModels['model'];
        $model = $controller::query();
        if (!empty($settingsListModels['dataTableLoad'])){
            $model->with($settingsListModels['dataTableLoad']);
        }
        return datatables()->eloquent($model)->toJson();
    }

    public function edit($settingsListModels, $model, Request $request)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];

        if($model = $settingsListModels['model']::where('id',$model)->first()){
            if (!empty($request->ajax())){
                return view('widget.bootstrap.list-models.modal', ['model' => $model, 'settingsListModels' => $settingsListModels, 'modalId' => 'edit_modal', 'title' => 'Edit '.$settingsListModels['name'], 'actionUrl' => route('dashboard.list-models.update', ['settingsListModels' => $settingsListModels['id'], 'model' => $model])]);
            }
            return view('dashboard.list-models.edit', compact('model', 'settingsListModels'));
        }
        abort(404);
    }
//$2y$10$6RnalvZrwciEwnl8dwJg4ukwH./ONox5bcbRTerGE8maR.EW3QeCK
    public function create($settingsListModels)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];
        $model = new $settingsListModels['model'];

        return view('dashboard.list-models.edit', compact('model', 'settingsListModels'));
    }

    public function store(Request $request, $settingsListModels)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];
        self::validateFields($settingsListModels, $request);
        $controller = $settingsListModels['model'];
        $model = new $controller;
        if ($this->save($model, $request, $settingsListModels)) {
            UserLog::add(null, $model);
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.list-models.index', ['settingsListModels' => $settingsListModels['id']]);
        }
        if (!empty($request->ajax)) {
            abort(404);
        }
        return redirect()->back();
    }

    public function update(Request $request, $settingsListModels, $model)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];
        self::validateFields($settingsListModels, $request);
        $controller = $settingsListModels['model'];
        if ($model = $controller::where('id', $model)->first()) {
            $modelCopy = clone $model;
            if ($this->save($model, $request, $settingsListModels)) {
                UserLog::add($modelCopy, $model);
                if (!empty($request->ajax)) {
                    return 1;
                }
                return redirect()->route('dashboard.list-models.index', ['settingsListModels' => $settingsListModels['id']]);
            }
            if (!empty($request->ajax)) {
                abort(404);
            }
            return redirect()->back();
        }
        abort(404);
    }

    public function destroy($settingsListModels, $model)
    {
        $settingsListModels = ListModels::$data[$settingsListModels];
        $controller = $settingsListModels['model'];
        if ($model = $controller::where('id', $model)->first()) {
            $modelCopy = clone $model;
            if ($model->delete()) {
                UserLog::add($modelCopy, null);
                if (!empty($request->ajax)) {
                    return 1;
                }
                return redirect()->route('dashboard.list-models.index', ['settingsListModels' => $settingsListModels['id']]);
            }
            if (!empty($request->ajax)) {
                abort(500);
            }
            return redirect()->back();
        }
        abort(404);
    }

    //--------------------------

    public function save(&$model, Request $request, $settingsListModels = null)
    {
        $fields = [];
        if($settingsListModels){
            $fields = array_keys($settingsListModels['fields']);
        }
        return BaseControllerRepository::loadToModelBase($model, $request, $fields, true, true);
    }
    public function validateFields($settingsListModels,Request $request)
    {
        $fieldsValidation = collect($settingsListModels['fields'])->map(function ($item, $key) use ($settingsListModels, $request){
            if (empty($item['validation'])){
                return '';
            }
            $uniqueString =  'unique:'.$settingsListModels['model'].','.$item['id'];
            $uniqueString = !empty($request->id) ? $uniqueString.','.$request->id.',id' : $uniqueString;
            $item['validation'] = str_replace('unique',$uniqueString, $item['validation']);
            return $item['validation'];
        });
        return $request->validate($fieldsValidation->toArray());
    }
}
