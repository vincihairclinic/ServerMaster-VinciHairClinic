<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Base\PageRequest;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Auth;

class PodcastController extends ApiController
{
    public function index(Podcast $podcast)
    {
        Podcast::setStaticVisible(Podcast::$publicColumns);
        PodcastEpisode::setStaticVisible(PodcastEpisode::$publicColumns);

        if(Auth::user()){
            if(!Auth::user()->viewed_podcasts()->where('podcast_id', $podcast->id)->first()){
                Auth::user()->viewed_podcasts()->attach($podcast);
            }
        }

        $podcast->load([
            'podcast_episodes',
        ]);

        return $this->respond([
            'podcast' => $podcast,
        ]);
    }

    public function list(PageRequest $request)
    {
        Podcast::setStaticVisible(Podcast::$publicColumns);
        PodcastEpisode::setStaticVisible(PodcastEpisode::$publicColumns);

        $models = PodcastEpisode::orderBy('sort')->orderBy('id', 'desc')->limit(30);

        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'podcast_episodes' => $models->get(),
        ]);
    }

    /*public function list(PageRequest $request)
    {
        Podcast::setStaticVisible(Podcast::$publicColumns);
        PodcastEpisode::setStaticVisible(PodcastEpisode::$publicColumns);

        $models = Podcast::with([
            'podcast_episodes',
        ])->orderBy('sort')->orderBy('id', 'desc')->limit(30);

        if(!empty($articleCategory)){
            $models->where('article_category_id', $articleCategory->id);
        }

        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'podcasts' => $models->get(),
        ]);
    }*/





}
