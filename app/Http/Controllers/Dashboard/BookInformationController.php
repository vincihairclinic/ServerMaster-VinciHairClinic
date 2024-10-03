<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\BookInformation\StoreRequest;
use App\Http\Requests\Dashboard\BookInformation\UpdateRequest;
use App\Models\Book;
use App\Models\BookInformation;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookInformationController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJson(Book $book, Request $request)
    {
        $model = BookInformation::where('book_id', $book->id)->orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(BookInformation $model)
    {
        return $model;
    }

    public function create()
    {
        $model = new BookInformation();
        return response(200);
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new BookInformation(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function update(BookInformation $model, UpdateRequest $request)
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
                BookInformation::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(BookInformation $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------

    public function save(BookInformation $model, Request $request)
    {
        BaseControllerRepository::loadToModelImage($model, $request);
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name',
            'content',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }
}
