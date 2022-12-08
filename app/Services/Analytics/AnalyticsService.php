<?php

namespace App\Services\Analytics;

use Illuminate\Database\Eloquent\Collection;

interface AnalyticsService
{
    public function getAnalytics(Filters $filters): Collection;

}
