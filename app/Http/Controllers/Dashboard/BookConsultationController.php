<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\UserSimulationRequest\StoreRequest;
use App\Http\Requests\Dashboard\UserSimulationRequest\UpdateRequest;
use App\Models\BookConsultation;
use App\Models\User;
use App\Models\UserSimulationRequest;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class BookConsultationController extends DashboardController
{
    public function index()
    {
        return view('dashboard.book-consultation.index');
    }

    public function indexJson(Request $request)
    {
        //$model = User::whereNotNull('phone_number')->whereNull('clinic_id');
        $model = BookConsultation::query();
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(BookConsultation $model)
    {
        return view('dashboard.book-consultation.edit', compact('model'));
    }

    public function update(BookConsultation $model)
    {
        $model->is_book_consultation_checked = !$model->is_book_consultation_checked;
        $model->save();
        return 1;
    }

}
