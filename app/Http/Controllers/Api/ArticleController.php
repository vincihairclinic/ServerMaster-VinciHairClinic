<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Base\PageRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ProductCategory;

class ArticleController extends ApiController
{
    public function index(Article $article)
    {
        Article::setStaticVisible(Article::$publicColumns);
        ArticleCategory::setStaticVisible(ArticleCategory::$publicColumns);

        $article->load([
            'article_category',
        ]);

        return $this->respond([
            'article' => $article,
        ]);
    }

    public function list(PageRequest $request, ArticleCategory $articleCategory = null)
    {
        Article::setStaticVisible(Article::$publicColumns);
        ArticleCategory::setStaticVisible(ArticleCategory::$publicColumns);

        $models = Article::with([
            'article_category',
        ])->orderBy('sort')->orderBy('id', 'desc')->limit(30);

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
            $models = Article::with([
                'article_category',
            ])->orderBy('sort')->orderBy('id', 'desc')->limit(30);
        }

        if(!empty($articleCategory)){
            $models->where('article_category_id', $articleCategory->id);
        }
        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'articles' => $models->get(),
        ]);
    }

    public function nextList(PageRequest $request, Article $article)
    {
        Article::setStaticVisible(Article::$publicColumns);
        ArticleCategory::setStaticVisible(ArticleCategory::$publicColumns);

        $models = Article::with([
            'article_category',
        ])->where('id', '<', $article->id)->where('article_category_id', $article->article_category_id)->orderBy('id', 'desc')->limit(10);

        if(!empty($request->page)){
            $models->offset(30 * ($request->page - 1));
        }

        return $this->respond([
            'articles' => $models->get(),
        ]);
    }

    public function listCategories()
    {
        ArticleCategory::setStaticVisible(ArticleCategory::$publicColumns);

        $models = ArticleCategory::orderBy('sort')->orderBy('id', 'desc');

        return $this->respond([
            'article_categories' => $models->get(),
        ]);
    }



}
