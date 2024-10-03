<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\BookPostAdditionalItem;
use App\Models\BookPreAdditionalItem;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookAdditionalItemController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJsonPreAdditionalItem($additional, Request $request)
    {
        $model = BookPreAdditionalItem::orderBy('sort', 'asc')->where('book_pre_additional_id', $additional);
        return datatables()->eloquent($model)->toJson();
    }

    public function indexJsonPostAdditionalItem($additional, Request $request)
    {
        $model = BookPostAdditionalItem::orderBy('sort', 'asc')->where('book_post_additional_id', $additional);
        return datatables()->eloquent($model)->toJson();
    }

    public function editPreAdditionalItem(BookPreAdditionalItem $model)
    {
        return $model;
    }

    public function editPostAdditionalItem(BookPostAdditionalItem $model)
    {
        return $model;
    }

    public function storePreAdditionalItem(Request $request)
    {
        if ($this->savePreAdditionalItem(new BookPreAdditionalItem(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function storePostAdditionalItem(Request $request)
    {
        if ($this->savePostAdditionalItem(new BookPostAdditionalItem(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePreAdditionalItem(BookPreAdditionalItem $model, Request $request)
    {
        if ($this->savePreAdditionalItem($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePostAdditionalItem(BookPostAdditionalItem $model, Request $request)
    {
        if ($this->savePostAdditionalItem($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function sortUpdatePreAdditionalItem(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPreAdditionalItem::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function sortUpdatePostAdditionalItem(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPostAdditionalItem::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroyPreAdditionalItem(BookPreAdditionalItem $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    public function destroyPostAdditionalItem(BookPostAdditionalItem $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------


    public function savePreAdditionalItem(BookPreAdditionalItem $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'book_pre_additional_id',
        ], true, true);
        return $result;
    }

    public function savePostAdditionalItem(BookPostAdditionalItem $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'book_post_additional_id',
        ], true, true);
        return $result;
    }
}
