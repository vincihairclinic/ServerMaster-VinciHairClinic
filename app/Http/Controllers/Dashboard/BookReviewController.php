<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\BookReview\StoreRequest;
use App\Http\Requests\Dashboard\BookReview\UpdateRequest;
use App\Models\BookReview;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookReviewController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson(Request $request)
    {
        $model = BookReview::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(BookReview $model)
    {
        return $model;
    }

    public function create()
    {
        $model = new BookReview();
        return response(200);
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new BookReview(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(BookReview $model, UpdateRequest $request)
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
                BookReview::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(BookReview $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(BookReview $model, Request $request)
    {
        BaseControllerRepository::loadToModelMedia($model, $request, 'video', 'video');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }
}
