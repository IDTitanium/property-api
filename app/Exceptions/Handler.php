<?php

namespace App\Exceptions;

use App\Traits\SendApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use SendApiResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Application has thrown an exception', [$e]);
        });

        $this->renderable(function (ValidationException $e) {
            return $this->sendApiResponse("Validation error", Response::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->sendApiResponse("Route not found", Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (Throwable $e) {
            return $this->sendApiResponse("An error has occured.", Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    }

}
