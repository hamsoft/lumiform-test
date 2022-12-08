<?php

namespace Tests\Feature\Models\Analytics;

use App\Models\Analytics\EndpointAnalytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\ColumnsChecker;
use Tests\TestCase;

class EndpointAnalyticsTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    public function testSchema()
    {
        $this->checkColumns(EndpointAnalytics::TABLE, [
            EndpointAnalytics::UUID,
            EndpointAnalytics::PATH,
            EndpointAnalytics::METHOD,
            EndpointAnalytics::NAME,
            EndpointAnalytics::USER_UUID,
        ]);
    }
}
