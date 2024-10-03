<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\BookInstruction\StoreRequest;
use App\Http\Requests\Dashboard\BookInstruction\UpdateRequest;
use App\Models\Book;
use App\Models\BookInformation;
use App\Models\BookPostInstruction;
use App\Models\BookPreInstruction;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookInstructionController extends DashboardController
{
    public function index()
    {
        abort(404);
    }

    public function indexJsonPreInstruction(Book $book, Request $request)
    {
        $model = BookPreInstruction::orderBy('sort', 'asc')->where('book_id', $book->id);
        return datatables()->eloquent($model)->toJson();
    }

    public function indexJsonPostInstruction(Book $book, Request $request)
    {
        $model = BookPostInstruction::orderBy('sort', 'asc')->where('book_id', $book->id);
        return datatables()->eloquent($model)->toJson();
    }

    public function editPreInstruction(BookPreInstruction $model)
    {
        return $model;
    }

    public function editPostInstruction(BookPostInstruction $model)
    {
        return $model;
    }

    public function storePreInstruction(StoreRequest $request)
    {
        if ($this->savePreInstruction(new BookPreInstruction(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function storePostInstruction(StoreRequest $request)
    {
        if ($this->savePostInstruction(new BookPostInstruction(), $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePreInstruction(BookPreInstruction $model, UpdateRequest $request)
    {
        if ($this->savePreInstruction($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function updatePostInstruction(BookPostInstruction $model, UpdateRequest $request)
    {
        if ($this->savePostInstruction($model, $request)) {
            return response(200);
        }
        abort(500);
    }

    public function sortUpdatePreInstruction(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPreInstruction::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function sortUpdatePostInstruction(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                BookPostInstruction::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroyPreInstruction(BookPreInstruction $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    public function destroyPostInstruction(BookPostInstruction $model, Request $request)
    {
        if ($model->delete()) {
            return response(200);
        }
        abort(500);
    }

    //--------------------------


    public function savePreInstruction(BookPreInstruction $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }

    public function savePostInstruction(BookPostInstruction $model, Request $request)
    {
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'title',
            'content',
            'language_key',
            'book_id',
        ], true, true);
        return $result;
    }
}
