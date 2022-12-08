<?php

namespace App\Services\Analytics;

use App\Models\Analytics\EndpointAnalytics;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsServiceImpl implements AnalyticsService
{

    public function getAnalytics(Filters $filters): Collection
    {
        $query = EndpointAnalytics::query();

        $path = $filters->getPathCondition();
        if (!empty($path)) {
            $query->where(EndpointAnalytics::PATH, $path);
        }

        $method = $filters->getMethodCondition();
        if (isset($method)) {
            $query->where(EndpointAnalytics::METHOD, $method);
        }

        return $query->groupBy([EndpointAnalytics::PATH, EndpointAnalytics::METHOD])
            ->select([
                EndpointAnalytics::PATH,
                EndpointAnalytics::METHOD,
                EndpointAnalytics::raw('count(' . EndpointAnalytics::UUID . ') as count'),
            ])
            ->get();
    }

}
