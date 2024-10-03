<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\BookAdditional\StoreRequest;
use App\Http\Requests\Dashboard\BookAdditional\UpdateRequest;
use App\Models\BookInformation;
use App\Models\BookPostAdditional;
use App\Models\BookPreAdditional;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookAdditionalController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJsonPreAdditional($book, Request $request)
    {
        $model = BookPreAdditional::orderBy('sort', 'asc')->where('book_id', $book);
        return datatables()->eloquent($model)->toJson();
    }

    public function indexJsonPostAdditional($book, Request $request)
    {
        $model = BookPostAdditional::orderBy('sort', 'asc')->where('book_id', $book);
        return datatables()->eloquent($model)->toJson();
    }

    public function editPreAdditional(BookPreAdditional $model)
    {
        return $model;
    }

    public function editPostAdditional(BookPostAdditional $model)
    {
        return $model;
    }

    public function storePreAdditional(StoreRequest $request)
    {
        if ($this->savePreAdditional(new BookPreAdditional(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function storePostAdditional(StoreRequest $request)
    {
        if ($this->savePostAdditional(new BookPostAdditional(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePreAdditional(BookPreAdditional $model, UpdateRequest $request)
    {
        if ($this->savePreAdditional($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePostAdditional(BookPostAdditional $model, UpdateRequest $request)
    {
        if ($this->savePostAdditional($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function sortUpdatePreAdditional(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPreAdditional::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function sortUpdatePostAdditional(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPostAdditional::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroyPreAdditional(BookPreAdditional $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    public function destroyPostAdditional(BookPostAdditional $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------


    public function savePreAdditional(BookPreAdditional $model, Request $request)
    {
        BaseControllerRepository::loadToModelMedias($model, $request, 'images');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }

    public function savePostAdditional(BookPostAdditional $model, Request $request)
    {
        BaseControllerRepository::loadToModelMedias($model, $request, 'images');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }
}
