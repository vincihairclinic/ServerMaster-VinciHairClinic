<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\Datasets\Gender;
use App\Models\File;
use App\Models\HairLossType;
use App\Models\HairType;
use App\Models\Language;
use App\Models\Procedure;
use App\Models\Setting;
use App\Models\SimulationRequestType;
use App\Models\Territory;

class ListController extends ApiController
{
    public function index()
    {
        Country::setStaticVisible(Country::$publicColumns);
        Territory::setStaticVisible(Territory::$publicColumns);
        Language::setStaticVisible(Language::$publicColumns);
        SimulationRequestType::setStaticVisible(SimulationRequestType::$publicColumns);

        return $this->respondContent([
            'take_a_few_photo_of_your_hair' => route('blob.file', 'take_a_few_photo_of_your_hair.mp4'),
            'facebook_url' => Setting::where('id', 'facebook_url')->first()->value,
            'instagram_url' => Setting::where('id', 'instagram_url')->first()->value,
            'twitter_url' => Setting::where('id', 'twitter_url')->first()->value,
            'Gender' => Gender::all(),
            'Territory' => Territory::orderBy('sort')->orderBy('id', 'desc')->get(),
            'Country' => Country::orderBy('sort')->orderBy('id', 'desc')->get(),
            'Language' => Language::orderBy('sort')->orderBy('id', 'desc')->get(),
            'SimulationRequestType' => SimulationRequestType::orderBy('sort')->orderBy('id', 'desc')->get(),
            'HairType' => HairType::orderBy('sort')->orderBy('id', 'desc')->get(),
        ]);
    }

    public function listAfterLogin()
    {
        Procedure::setStaticVisible(Procedure::$publicColumns);
        Clinic::setStaticVisible(Clinic::$publicColumns);
        HairLossType::setStaticVisible(HairLossType::$publicColumns);
        SimulationRequestType::setStaticVisible(SimulationRequestType::$publicColumns);
        HairType::setStaticVisible(HairType::$publicColumns);

        return $this->respondContent([
            'Procedure' => Procedure::orderBy('sort')->orderBy('id', 'desc')->get(),
            'Clinic' => Clinic::with('country.territory')->orderBy('sort')->orderBy('id', 'desc')->get(),
            'HairLossType' => HairLossType::orderBy('sort')->orderBy('id', 'desc')->get(),
            'SimulationRequestType' => SimulationRequestType::orderBy('sort')->orderBy('id', 'desc')->get(),
            'HairType' => HairType::orderBy('sort')->orderBy('id', 'desc')->get(),
        ]);
    }
}
