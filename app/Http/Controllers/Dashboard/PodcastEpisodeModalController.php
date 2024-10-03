<?php

namespace App\Http\Controllers\Dashboard;

use App\AppConf;
use App\Http\Requests\Dashboard\PodcastEpisode\StoreRequest;
use App\Http\Requests\Dashboard\PodcastEpisode\UpdateRequest;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class PodcastEpisodeModalController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson(Podcast $model, Request $request)
    {
        $model = PodcastEpisode::where('podcast_id', $model->id)->orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(PodcastEpisode $model)
    {
        return $model;
    }

    public function create()
    {
        $model = new PodcastEpisode();
        return response(200);
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new PodcastEpisode(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(PodcastEpisode $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return response(200);
        }
        abort(500);
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
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(PodcastEpisode $model, Request $request)
    {
        ini_set('max_execution_time', AppConf::$max_execution_time_video);
        ini_set('memory_limit', AppConf::$memory_limit_video);
        BaseControllerRepository::loadToModelMedia($model, $request, 'file', 'file');
        BaseControllerRepository::loadToModelMedia($model, $request, 'image', 'image');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'duration_min',
            'content_en',
            'content_pt',
            'podcast_id',
            'duration_min',
        ], true, true);
        return $result;
    }
}
