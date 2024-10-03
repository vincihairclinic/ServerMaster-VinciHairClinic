<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends DashboardController
{
    public function index($model)
    {
        if ($model = Page::where('route', $model)->first()){
            return view('dashboard.page.index', compact('model'));
        }
        abort(404);
    }

    public function update($model, Request $request)
    {
        if ($model = Page::where('route', $model)->first()) {
            $model->html = $request->html;
            $model->save();
            return redirect()->route('dashboard.page.index', ['model' => $model->route, 'success' => 1]);
        }
        abort(404);
    }

}
