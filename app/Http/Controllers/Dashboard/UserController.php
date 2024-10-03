<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Models\User;
use App\Repositories\Base\BaseControllerRepository;
use App\Repositories\Base\BlobRepository;
use Illuminate\Http\Request;

class UserController extends DashboardController
{
    public function index()
    {
        return view('dashboard.user.index');
    }

    public function indexJson(Request $request)
    {
        $model = User::where('email', 'NOT LIKE', '_t_m_p_%');
        return datatables()->eloquent($model)->toJson();
    }

    public function edit(User $model)
    {
        return view('dashboard.user.edit', compact('model'));
    }

    public function create()
    {
        $model = new User();
        return view('dashboard.user.edit', compact('model'));
    }

    public function store(StoreRequest $request)
    {
        if ($this->save(new User(), $request)) {
            return redirect()->route('dashboard.user.index');
        }
        return redirect()->back();
    }

    public function update(User $model, UpdateRequest $request)
    {
        if ($this->save($model, $request)) {
            return redirect()->route('dashboard.user.index');
        }
        return redirect()->back();
    }

    public function destroy(User $model, Request $request)
    {
        if ($model->delete()) {
            if (!empty($request->ajax)) {
                return 1;
            }
            return redirect()->route('dashboard.user.index');
        }
        if (!empty($request->ajax)) {
            abort(500);
        }
        return redirect()->back();
    }

    //--------------------------

    public function save(User $model, Request $request)
    {
        if ($request->has('password')) {
            $model->password = \Hash::make(hash('sha256', $request->password));
        }
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_front_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_side_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_back_image');
        BaseControllerRepository::loadToModelImage($model, $request, 'hair_top_image');
        BaseControllerRepository::loadToModelBool($model, $request, [
            'would_you_like_to_get_in_touch_with_you',
            'does_your_family_suffer_from_hereditary_hair_loss',
            'is_show_news_and_updates_notification',
            'is_show_promotions_and_offers_notification',
            'is_show_insights_and_tips_notification',
            'is_show_new_articles_notification',
            'is_show_requests_and_tickets_notification',
        ]);
        $model->is_email_verified = 1;
        $model->app_state = 'completed_state';
        $result = BaseControllerRepository::loadToModelBase($model, $request, [
            'full_name',
            'email',
            'age',
            'phone_number',
            'gender_id',
            'clinic_id',
            'hair_loss_type_id',
            'hair_type_id',
            'country_id',
            'language_key',
            'how_long_have_you_experienced_hair_loss_for',
        ], true, true);
        $model->procedures()->sync($request->procedures);
        return $result;
    }
}
