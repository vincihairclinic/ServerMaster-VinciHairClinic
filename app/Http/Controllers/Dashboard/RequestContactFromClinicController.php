<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\UserSimulationRequest\StoreRequest;
use App\Http\Requests\Dashboard\UserSimulationRequest\UpdateRequest;
use App\Models\User;
use App\Models\UserSimulationRequest;
use App\Repositories\Base\BaseControllerRepository;
use Illuminate\Http\Request;

class RequestContactFromClinicController extends DashboardController
{
    public function index()
    {
        return view('dashboard.request-contact-from-clinic.index');
    }

    public function indexJson(Request $request)
    {
        $model = User::whereNotNull('phone_number')->whereNotNull('clinic_id');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(User $model)
    {
        return view('dashboard.request-contact-from-clinic.edit', compact('model'));
    }

    public function update(User $model)
    {
        $model->is_book_consultation_checked = !$model->is_book_consultation_checked;
        $model->save();
        return 1;
    }

}
