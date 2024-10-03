<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ProductCategory\StoreRequest;
use App\Http\Requests\Dashboard\ProductCategory\UpdateRequest;
use App\Models\ProductCategory;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProductCategoryController extends DashboardController
{
    public function index()
    {
        return view('dashboard.product-category.index');
    }

    public function indexJson(Request $request)
    {
        $model = ProductCategory::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ProductCategory $model)
    {
        return view('dashboard.product-category.edit', compact('model'));
    }

    public function create()
    {
        $model = new ProductCategory();
        return view('dashboard.product-category.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ProductCategory(), $request)) {
            return redirect()->route('dashboard.product-category.index');
        }
        return redirect()->back();
    }

    public function update(ProductCategory $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.product-category.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                ProductCategory::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ProductCategory $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.product-category.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(ProductCategory $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
        ], true, true);
        return $result;
    }
}
