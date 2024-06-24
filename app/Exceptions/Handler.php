<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Response;
use App\Exceptions\ProductDeletionException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ErrorRegisteringUserException;
use App\Exceptions\ErrorFindingProductException;
use App\Exceptions\ErrorUpdatingProductException;
use App\Exceptions\ErrorLoginException;
use App\Exceptions\ErrorLogoutException;
use App\Exceptions\ErrorSavingProductException;
use App\Exceptions\SomethingHasErrorException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Các loại ngoại lệ không cần báo cáo
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof SomethingHasErrorException)
        {
            return response()->json([
                'message' => __('messages.error_something_has_error'),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        throw new \RuntimeException("An error occurred: ". $exception);
    }
}
