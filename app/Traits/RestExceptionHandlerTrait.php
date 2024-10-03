<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait RestExceptionHandlerTrait
{
    use RestTrait;

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Throwable $e)
    {
        if (!$this->isJsonRequest($request)) {
            return false;
        }

        $title = 'Woops!';

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'title'     => $title,
                'message'     => 'Resource not available',
                'status_code' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);

        } else if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'title'     => $title,
                'message'     => 'Endpoint not found',
                'status_code' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);

        } else if ($e instanceof ValidationException) {

            $message = collect($e->errors())->first()[0];
            if($message == 'Please enter a valid email format and try again'){
                $title = 'Invalid email format';
            }
            return response()->json([
                'title'     => $title,
                'message'     => collect($e->errors())->first()[0],
                'status_code' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);

        } else if ($e instanceof AuthenticationException) {
            return response()->json([
                'title'     => $title,
                'message'     => 'Forbidden',
                'status_code' => Response::HTTP_FORBIDDEN,
            ], Response::HTTP_FORBIDDEN);
        }else if ($e instanceof AuthenticationException || strpos($e->getTraceAsString(), 'abort(403)') !== false) {
            return response()->json([
                'title'     => $title,
                'message'     => 'Forbidden',
                'status_code' => Response::HTTP_FORBIDDEN,
            ], Response::HTTP_FORBIDDEN);
        }


        if(!config('app.debug')){
            return response()->json([
                'title'     => $title,
                'message'     => 'Bad request',
                'status_code' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}