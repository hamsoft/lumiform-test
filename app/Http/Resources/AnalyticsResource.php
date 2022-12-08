<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Analytics\EndpointAnalytics $resource
 */
class AnalyticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'endpoint' => $this->resource->path,
            'method' => $this->resource->method,
            'count' => $this->resource->count ?? 0,
        ];
    }
}
