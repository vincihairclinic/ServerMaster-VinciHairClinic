<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Product\StoreRequest;
use App\Http\Requests\Dashboard\Product\UpdateRequest;
use App\Models\Product;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProductController extends DashboardController
{
    public function index()
    {
        return view('dashboard.product.index');
    }

    public function indexJson(Request $request)
    {
        $model = Product::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Product $model)
    {
        return view('dashboard.product.edit', compact('model'));
    }

    public function create()
    {
        $model = new Product();
        return view('dashboard.product.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Product(), $request)) {
            return redirect()->route('dashboard.product.index');
        }
        return redirect()->back();
    }

    public function update(Product $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.product.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Product::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Product $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.product.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Product $model, Request $request)
    {
        $request->merge([
            'faqs_en' => array_map(function ($el) {
                return (object)$el;
            }, array_values($request->faqs_en ?? [])),
            'faqs_pt' => array_map(function ($el) {
                return (object)$el;
            }, array_values($request->faqs_pt ?? [])),
            'shop_now_urls' => array_map(function ($el) {
                return (object)$el;
            }, array_values($request->shop_now_urls ?? [])),
        ]);
        BaseControllerRepository::loadToModelImages($model, $request);
        BaseControllerRepository::loadToModelMedia($model, $request, 'video_en', 'video');
        BaseControllerRepository::loadToModelMedia($model, $request, 'video_pt', 'video');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'about_en',
            'about_pt',
            'video_name_en',
            'video_name_pt',
            'faqs_en',
            'faqs_pt',
            'shop_now_urls',
            'product_category_id',
        ], true, true);
        return $result;
    }
}
