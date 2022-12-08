<?php

namespace Tests\Feature\Models;

use App\Models\ResponseSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResponseSetTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    /**
     * @return void
     *
     */
    public function testSchema(): void
    {
        $this->checkColumns(ResponseSet::TABLE, [
            ResponseSet::UUID,
        ]);
    }
}
