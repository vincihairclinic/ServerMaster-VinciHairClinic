<?php

namespace App\Exceptions;

use App\Traits\RestExceptionHandlerTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RestExceptionHandlerTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (false && !config('app.debug') && !$e instanceof NotFoundHttpException && !$e instanceof AuthenticationException /* && $e instanceof \ErrorException*/) {
            http_response_code(503);
            header($_SERVER["SERVER_PROTOCOL"]." 503 Service Temporarily Unavailable", true, 503);

            $retryAfterSeconds = 480;
            header('Retry-After: ' . $retryAfterSeconds);

            echo '<h1>503 Service Temporarily Unavailable</h1>';
            echo '<p>Our site is currently under maintenance.</p>';

            exit();
        }else if(!$response = $this->getJsonResponseForException($request, $e)){
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->back()->withInput()->with('token', csrf_token());
            }
            $response = parent::render($request, $e);
        }

        return $response;
    }
}
