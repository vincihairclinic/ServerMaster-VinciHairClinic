<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends DashboardController
{
    protected function respond($data, $statusCode = Response::HTTP_OK, $headers = [])
    {
        $data = is_object($data) ? (array)$data : $data;
        return response()->json($data, $statusCode, $headers);
    }

    protected function respondContent($data = false)
    {
        if (!empty($data)) {
            return $data;
        }
        return $this->respondNoContent();
    }

    protected function respondNoContent()
    {
        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }

    protected function respondForbidden($title = 'Woops!')
    {
        return response()->json([
            'title'     => $title,
            'message'     => 'Forbidden',
            'status_code' => Response::HTTP_FORBIDDEN,
        ], Response::HTTP_FORBIDDEN);
    }

    protected function respondNotFound($errors = 'Not found', $title = 'Woops!', $statusCode = Response::HTTP_NOT_FOUND)
    {
        return $this->respond([
            'title'     => 'Woops!',
            'message'     => $errors,
            'status_code' => $statusCode,
        ], $statusCode);
    }

    protected function respondSuccess($data = ['message' => 'Success', 'title' => ''])
    {
        return $this->respond($this->checkMessage($data));
    }

    protected function respondCreated($data = ['message' => 'Created successfully', 'title' => ''])
    {
        return $this->respond($this->checkMessage($data), Response::HTTP_CREATED);
    }

    protected function respondError($errors = 'Undefined error', $title = 'Woops!', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return $this->respond([
            'title'     => $title,
            'message'     => $errors,
            'status_code' => $statusCode,
        ], $statusCode);
    }

    protected function respondSubscriptionExpired()
    {
        return $this->respondError('Subscription has expired');
    }

    protected function respondValidationError(Validator $validator)
    {
        return $this->respond([
            'title'     => 'Woops!',
            'message'     => 'The given data was invalid',
            'status_code' => Response::HTTP_BAD_REQUEST,
        ], Response::HTTP_BAD_REQUEST);
    }

    protected function checkMessage($data)
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if(empty($data['status_code'])){
            $data['status_code'] = Response::HTTP_OK;
        }
        return $data;
    }
}