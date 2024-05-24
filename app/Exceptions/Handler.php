<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Response;
use App\Exceptions\ProductDeletionException;
use App\Exceptions\ProductNotFoundException;

class Handler extends ExceptionHandler
{
    // Rest of the code...

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ProductNotFoundException) {
            return response()->json([
                'message' => product_not_found(),
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ProductDeletionException) {
            return response()->json([
                'message' => error_deleting_product(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}