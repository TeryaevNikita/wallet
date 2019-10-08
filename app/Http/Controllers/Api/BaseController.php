<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    protected function success(array $data = [], int $code = 200): JsonResponse
    {
        $msg = $msg ?? 'Success';

        return response()->json($data, $code);
    }

    protected function error(string $msg = null, int $code = 400): JsonResponse
    {
        $msg = $msg ?? 'Error';
        return response()->json([
            'message' => $msg,
        ], 400);
    }
}

