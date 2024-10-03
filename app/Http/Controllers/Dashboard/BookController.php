<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Book\StoreRequest;
use App\Http\Requests\Dashboard\Book\UpdateRequest;
use App\Models\Book;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookController extends DashboardController
{
    public function index()
    {
        return view('dashboard.book.index');
    }

    public function indexJson(Request $request)
    {
        $model = Book::orderBy('sort', 'asc');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(Book $model)
    {
        return view('dashboard.book.edit', compact('model'));
    }

    public function create()
    {
        $model = new Book();
        return view('dashboard.book.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new Book(), $request)) {
            return redirect()->route('dashboard.book.index');
        }
        return redirect()->back();
    }

    public function update(Book $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.book.index');
        }
        return redirect()->back();
    }

    public function sortUpdate(Request $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {
                Book::where('id', $data[0])->update([
                    'sort' => $data[1]
                ]);
            }
            return 1;
        }
        return 0;
    }

    public function destroy(Book $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.book.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(Book $model, Request $request)
    {
        $request->merge([
            'faqs_en' => array_map(function ($el) {
                return (object)$el;
            }, array_values($request->faqs_en ?? [])),
            'faqs_pt' => array_map(function ($el) {
                return (object)$el;
            }, array_values($request->faqs_pt ?? []))
        ]);
        BaseControllerRepository::loadToModelImage($model, $request);
        BaseControllerRepository::loadToModelImage($model, $request, 'pre_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'post_image');
        BaseControllerRepository::loadToModelMedia($model, $request, 'video_en', 'video');
        BaseControllerRepository::loadToModelMedia($model, $request, 'video_pt', 'video');
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'name_en',
            'name_pt',
            'about_en',
            'about_pt',
            'faqs_en',
            'faqs_pt',
            'pre_name_en',
            'pre_name_pt',
            'pre_content_en',
            'pre_content_pt',
            'post_name_en',
            'post_name_pt',
            'post_content_en',
            'post_content_pt',
        ], true, true);
        return $result;
    }
}
