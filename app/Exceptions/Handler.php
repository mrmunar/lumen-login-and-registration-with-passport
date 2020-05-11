<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    protected $error404Classes = [
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class
    ];

    protected $error500Classes = [
        ApiConnectionException::class,
        ConfigurationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $message = $exception->getMessage();

        if ($exception instanceof InvalidInputException) {
            return $this->responseError($message, 400);
        }

        if ($exception instanceof InvalidLoginException) {
            return $this->responseError($message, 401);
        }

        if (isOneOfInstances($exception, $this->error404Classes)) {
            return response()->json('Resource not found', 404);
        }

        if (isOneOfInstances($exception, $this->error500Classes)) {
            return $this->responseError($message, 500);
        }

        return parent::render($request, $exception);
    }

    private function responseError(string $message, int $statusCode)
    {
        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => $message
        ], $statusCode);
    }
}
