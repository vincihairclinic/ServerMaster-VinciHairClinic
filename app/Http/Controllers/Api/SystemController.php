<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Http\Requests\Api\System\GeocodeByLatLngRequest;
use App\Http\Requests\Api\System\UploadImagesRequest;
use App\Repositories\Base\BlobRepository;
use App\Repositories\Base\CurlRepository;
use Illuminate\Http\Request;

class SystemController extends ApiController
{
    public function uploadImages(UploadImagesRequest $request)
    {
        $response = BlobRepository::filesSave($request, 'images', 'image', 'maxSize', 1024);
        foreach ($response as $i => $v){
            $response[$i] = Application::storageImageAsset($v);
        }

        return $this->respond($response);
    }

    public function uploadFiles(Request $request)
    {
        $response = BlobRepository::filesSave($request, 'files', 'file');
        foreach ($response as $i => $v){
            $response[$i] = Application::storageImageAsset($v);
        }

        return $this->respond($response);
    }


    public function geocodeByLatLng(GeocodeByLatLngRequest $request)
    {
        return $this->respond(CurlRepository::sendCurl('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$request->latlng.'&key='.env('GOOGLE_GEOCODE_KEY'), [], 'get', true));
    }

    public function geocodeAutocomplete(Request $request)
    {
        return $this->respond(CurlRepository::sendCurl('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($request->input).'&key='.env('GOOGLE_GEOCODE_KEY'), [], 'get', true));
    }

}
