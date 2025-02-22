<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RestTrait
{

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/v'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        return strpos($request->getUri(), '//api.') !== false;
    }

    protected function isJsonRequest(Request $request)
    {
        if ($request->wantsJson() || $this->isApiCall($request)) {
            return true;
        }
        return false;
    }

}