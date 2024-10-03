<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Base\PageRequest;
use App\Models\Article;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\Video;
use Auth;

class VideoController extends ApiController
{
    public function list(PageRequest $request)
    {
        Video::setStaticVisible(Video::$publicColumns);

        $models = Video::orderBy('id', 'desc')->limit(30);
        if(!empty(\Auth::user())){
            $models->where(function ($q){
                if($user_procedure_ids = \Auth::user()->procedures->pluck('id')->all()){
                    $q->orWhereHas('tag_procedures', function ($q) use ($user_procedure_ids){
                        $q->whereIn('id', $user_procedure_ids);
                    });
                }

                if(!empty(\Auth::user()->gender_id)){
                    $q->orWhereHas('tag_genders', function ($q){
                        $q->where('id', \Auth::user()->gender_id);
                    });
                }

                if(!empty(\Auth::user()->hair_type_id)){
                    $q->orWhereHas('tag_hair_types', function ($q){
                        $q->where('id', \Auth::user()->hair_type_id);
                    });
                }
            });
        }
        $models_clone = clone $models;
        if(!$models_clone->first()){
            $models = Video::orderBy('id', 'desc')->limit(30);
        }

        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'videos' => $models->get(),
        ]);
    }





}
