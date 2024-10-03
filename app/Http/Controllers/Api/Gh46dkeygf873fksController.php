<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Http\Requests\Api\System\GeocodeByLatLngRequest;
use App\Http\Requests\Api\System\UploadImagesRequest;
use App\Repositories\Base\BlobRepository;
use App\Repositories\Base\CurlRepository;
use Illuminate\Http\Request;

class Gh46dkeygf873fksController extends ApiController
{
    public function emailVerify()
    {
        \Auth::user()->update([
            'is_email_verified' => 1
        ]);

        return $this->respondSuccess();
    }


}
