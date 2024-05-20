<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $items = $this->repository->all();
        return response()->json(
            [
                'contents' => $items,
                'status' => Response::HTTP_OK
            ]
        );
    }
}
