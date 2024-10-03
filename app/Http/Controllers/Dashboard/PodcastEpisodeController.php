<?php

namespace App\Http\Controllers\Dashboard;

use App\AppConf;
use App\Http\Requests\Dashboard\PodcastEpisode\StoreRequest;
use App\Http\Requests\Dashboard\PodcastEpisode\UpdateRequest;
use App\Models\PodcastEpisode;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class PodcastEpisodeController extends DashboardController
{
    public function index()
    {
        return view('dashboard.podcast-episode.index');
    }

    public function indexJson(Request $request)
    {
        $model = PodcastEpisode::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(PodcastEpisode $model)
    {
        return view('dashboard.podcast-episode.edit', compact('model'));
    }

    public function create()
    {
        $model = new PodcastEpisode();
        return view('dashboard.podcast-episode.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new PodcastEpisode(), $request)) {
            return redirect()->route('dashboard.podcast-episode.index');
        }
        return redirect()->back();
    }

    public function update(PodcastEpisode $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.podcast-episode.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                PodcastEpisode::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(PodcastEpisode $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.podcast-episode.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(PodcastEpisode $model, Request $request)
    {
        ini_set('max_execution_time', AppConf::$max_execution_time_video);
        ini_set('memory_limit', AppConf::$memory_limit_video);
        BaseControllerRepository::loadToModelMedia($model, $request, 'file_en', 'file');
        BaseControllerRepository::loadToModelMedia($model, $request, 'file_pt', 'file');
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'duration_min',
            'content_en',
            'content_pt',
            'duration_min',
        ], true, true);
        $model->tag_procedures()->sync($request->tag_procedures);
        $model->tag_hair_types()->sync($request->tag_hair_types);
        $model->tag_genders()->sync($request->tag_genders);
        return $result;
    }
}
