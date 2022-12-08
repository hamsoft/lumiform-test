<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Analytics\GetAnalyticsRequest;
use App\Http\Requests\Api\Form\NewFormRequest;
use App\Http\Resources\AnalyticsResource;
use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Services\Analytics\AnalyticsService;
use App\Services\FormService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends ApiController
{

    /**
     * Create new form
     *
     * @param \App\Http\Requests\Api\Analytics\GetAnalyticsRequest $request
     * @param \App\Services\Analytics\AnalyticsService $analyticsService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetAnalyticsRequest $request, AnalyticsService $analyticsService): JsonResponse
    {
        $data = $analyticsService->getAnalytics($request);

        return $this->storeResponse(AnalyticsResource::collection($data));
    }
}
