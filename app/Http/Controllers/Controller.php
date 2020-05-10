<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return [
            'success' => false,
            'code' => 422,
            'message' => 'Input validation error',
            'errors' => $errors
        ];
    }

    public function responseSuccess(array $array = [], int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $statusCode,
            'message' => array_get($array, 'message', ''),
            'data' => array_get($array, 'data', [])
        ], $statusCode);
    }

    public function responseError(array $array = [], int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => array_get($array, 'message'),
            'errors' => array_get($array, 'errors'),
        ], $statusCode);
    }
}
