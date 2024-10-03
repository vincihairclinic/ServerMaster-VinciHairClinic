<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Article\StoreRequest;
use App\Http\Requests\Dashboard\Article\UpdateRequest;
use App\Models\Article;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ArticleController extends DashboardController
{
    public function index()
    {
        return view('dashboard.article.index');
    }

    public function indexJson(Request $request)
    {
        $model = Article::query();
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Article $model)
    {
        return view('dashboard.article.edit', compact('model'));
    }

    public function create()
    {
        $model = new Article();
        return view('dashboard.article.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Article(), $request)) {
            return redirect()->route('dashboard.article.index');
        }
        return redirect()->back();
    }

    public function update(Article $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.article.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Article::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Article $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.article.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Article $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        BaseControllerRepository::loadToModelBool($model, $request,[
            'is_for_male',
            'is_for_female'
        ]);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'content_en',
            'content_pt',
            'article_category_id',
        ], true, true);
        $model->procedures()->sync($request->procedures);
        $model->tag_procedures()->sync($request->tag_procedures);
        $model->tag_hair_types()->sync($request->tag_hair_types);
        $model->tag_genders()->sync($request->tag_genders);
        return $result;
    }
}
