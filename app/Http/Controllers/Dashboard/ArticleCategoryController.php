<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ArticleCategory\StoreRequest;
use App\Http\Requests\Dashboard\ArticleCategory\UpdateRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ArticleCategoryController extends DashboardController
{
    public function index()
    {
        return view('dashboard.article-category.index');
    }

    public function indexJson(Request $request)
    {
        $model = ArticleCategory::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ArticleCategory $model)
    {
        return view('dashboard.article-category.edit', compact('model'));
    }

    public function create()
    {
        $model = new ArticleCategory();
        return view('dashboard.article-category.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ArticleCategory(), $request)) {
            return redirect()->route('dashboard.article-category.index');
        }
        return redirect()->back();
    }

    public function update(ArticleCategory $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.article-category.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                ArticleCategory::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ArticleCategory $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.article-category.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(ArticleCategory $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
        ], true, true);
        return $result;
    }
}
