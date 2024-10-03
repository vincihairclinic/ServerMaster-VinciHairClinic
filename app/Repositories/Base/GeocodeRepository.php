<?php

namespace App\Repositories\Base;

use App\Http\Requests\Api\System\GeocodeByLatLngRequest;
use Illuminate\Http\Request;

class GeocodeRepository
{
    static function getAddressByLatLng($latlng)
    {
        $response = CurlRepository::sendCurl('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&key='.env('GOOGLE_GEOCODE_KEY'), [], 'get', true);
        return $response;
    }

    static function getLatLngByAddress($address)
    {
        if(!empty($address)){
            if($response = CurlRepository::sendCurl('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.env('GOOGLE_GEOCODE_KEY'), [], 'get', true)){
                if(!empty($response->results) && !empty($response->results[0]) && !empty($response->results[0]->geometry) && !empty($response->results[0]->geometry->location)){
                    return $response->results[0]->geometry->location;
                }
            }
        }

        return (object)[
            'lat' => null,
            'lng' => null,
        ];
    }
}