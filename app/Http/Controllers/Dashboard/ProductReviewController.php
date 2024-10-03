<?php

namespace App\Http\Controllers\Dashboard;

use App\AppConf;
use App\Http\Requests\Dashboard\ProductReview\StoreRequest;
use App\Http\Requests\Dashboard\ProductReview\UpdateRequest;
use App\Models\Product;
use App\Models\ProductReview;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class ProductReviewController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson(Product $model, Request $request)
    {
        $model = ProductReview::where('product_id', $model->id)->orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(ProductReview $model)
    {
        return $model;
    }

    public function create()
    {
        $model = new ProductReview();
        return response(200);
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new ProductReview(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(ProductReview $model, UpdateRequest $request)
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
                ProductReview::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(ProductReview $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(ProductReview $model, Request $request)
    {
        ini_set('max_execution_time', AppConf::$max_execution_time_video);
        ini_set('memory_limit', AppConf::$memory_limit_video);
        BaseControllerRepository::loadToModelMedia($model, $request, 'video', 'video');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name',
            'language_key',
            'product_id',
        ], true, true);
        return $result;
    }
}
