<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    /**
     * @param mixed $data
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response(mixed $data = [], int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * @param mixed $data
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function storeResponse(mixed $data, int $status = 201): JsonResponse
    {
        return $this->response($data, $status);
    }
}
