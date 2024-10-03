<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Podcast\StoreRequest;
use App\Http\Requests\Dashboard\Podcast\UpdateRequest;
use App\Models\Podcast;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class PodcastController extends DashboardController
{
    public function index()
    {
        return view('dashboard.podcast.index');
    }

    public function indexJson(Request $request)
    {
        $model = Podcast::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Podcast $model)
    {
        return view('dashboard.podcast.edit', compact('model'));
    }

    public function create()
    {
        $model = new Podcast();
        return view('dashboard.podcast.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Podcast(), $request)) {
            return redirect()->route('dashboard.podcast.index');
        }
        return redirect()->back();
    }

    public function update(Podcast $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.podcast.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Podcast::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Podcast $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.podcast.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Podcast $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'content_en',
            'content_pt',
        ], true, true);
        return $result;
    }
}
