<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Book;
use App\Models\BookInformation;
use App\Models\BookPostAdditional;
use App\Models\BookPostAdditionalItem;
use App\Models\BookPostInstruction;
use App\Models\BookPreAdditional;
use App\Models\BookPreAdditionalItem;
use App\Models\BookPreInstruction;
use App\Models\BookReview;
use App\Models\Datasets\Gender;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\Procedure;
use App\Models\ProcedureResult;
use App\Models\ProcedureResultImage;
use App\Models\ProcedureResultVideo;
use App\Repositories\Base\ServerRepository;

class IndexController extends ApiController
{
    public function home()
    {
        Article::setStaticVisible(Article::$publicColumns);
        ArticleCategory::setStaticVisible(ArticleCategory::$publicColumns);
        ProcedureResult::setStaticVisible(ProcedureResult::$publicColumns);
        ProcedureResultImage::setStaticVisible(ProcedureResultImage::$publicColumns);
        ProcedureResultVideo::setStaticVisible(ProcedureResultVideo::$publicColumns);
        Procedure::setStaticVisible(Procedure::$publicColumns);
        Book::setStaticVisible(Book::$publicColumns);
        BookReview::setStaticVisible(BookReview::$publicColumns);
        BookInformation::setStaticVisible(BookInformation::$publicColumns);
        BookPostInstruction::setStaticVisible(BookPostInstruction::$publicColumns);
        BookPostAdditional::setStaticVisible(BookPostAdditional::$publicColumns);
        BookPostAdditionalItem::setStaticVisible(BookPostAdditionalItem::$publicColumns);
        BookPreInstruction::setStaticVisible(BookPreInstruction::$publicColumns);
        BookPreAdditional::setStaticVisible(BookPreAdditional::$publicColumns);
        BookPreAdditionalItem::setStaticVisible(BookPreAdditionalItem::$publicColumns);
        Podcast::setStaticVisible(Podcast::$publicColumns);
        PodcastEpisode::setStaticVisible(PodcastEpisode::$publicColumns);

        /*$article = Article::with('article_category')->whereHas('procedures', function ($q){
            $q->whereIn('id', \Auth::user()->procedures->pluck('id'));
        })->where(\Auth::user()->gender_id == Gender::MALE ? 'is_for_male' : 'is_for_female', 1)->first();*/

        $articles = Article::with('article_category')->orderBy('sort')->orderBy('created_at', 'desc')->limit(10);

        if(!empty(\Auth::user())){
            $articles->where(function ($q){
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

        $articles = $articles->get();
        if($articles->isEmpty()){
            $articles = Article::with('article_category')->orderBy('sort')->orderBy('created_at', 'desc')->limit(10)->get();
        }

        //---------------------------

        $procedure_results = ProcedureResult::with([
            'procedure',
            'procedure_result_videos' => function($q){
                $q->orderBy('sort');
            },
            'procedure_result_images' => function($q){
                $q->orderBy('sort');
            },
        ])->orderBy('sort')->orderBy('created_at', 'desc');

        if(!empty(\Auth::user())){
            $procedure_results->where(function ($q){
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

        $procedure_results = $procedure_results->get();
        if($procedure_results->isEmpty()){
            $procedure_results = ProcedureResult::with([
                'procedure',
                'procedure_result_videos' => function($q){
                    $q->orderBy('sort');
                },
                'procedure_result_images' => function($q){
                    $q->orderBy('sort');
                },
            ])->orderBy('sort')->orderBy('created_at', 'desc')->get();
        }

        //---------------------------

        $podcast_episodes = PodcastEpisode::orderBy('sort')->orderBy('id', 'desc')->limit(10);
        /*if(!empty(\Auth::user())){
            $podcast_episodes->whereDoesntHave('user_viewed_podcast', function ($q){
                $q->where('user_id', \Auth::id());
            });
        }*/

        if(!empty(\Auth::user())){
            $podcast_episodes->where(function ($q){
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

        $podcast_episodes = $podcast_episodes->get();
        if($podcast_episodes->isEmpty()){
            $podcast_episodes = PodcastEpisode::orderBy('sort')->orderBy('id', 'desc')->limit(10)->get();
        }

        //---------------------------

        return $this->respond([
            'podcasts' => [],
            'podcast_episodes' => $podcast_episodes,
            'article' => null,
            'articles' => $articles,
            'procedure_results' => $procedure_results,
            'books' => Book::with([
                'book_reviews',
                'book_informations',
                'book_pre_instructions',
                'book_pre_additionals.book_pre_additional_items',
                'book_post_instructions',
                'book_post_additionals.book_post_additional_items',
            ])->get(),
        ]);
    }

    public function apiVersion()
    {
        //TODO:version

        return $this->respondContent('0.0.1');
    }

    public function statusGet()
    {
        return $this->respond([
            'version' => 1,
            'loadavg' => [sys_getloadavg()[0], ServerRepository::parseRamSpace(), ServerRepository::parseDiscSpace(), ServerRepository::parseDiscSpace('/dev/sdb')],
            'processList' => [],
            'description' => Application::getDescriptionServer(),
        ]);
    }

    public function clearView()
    {
        shell_exec('php '.base_path().'/artisan view:clear');
        shell_exec('php '.base_path().'/artisan clear-compiled');
        shell_exec('php '.base_path().'/artisan config:clear');

        return $this->respond(1);
    }
}
