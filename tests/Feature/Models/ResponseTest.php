<?php

namespace Tests\Feature\Models;

use App\Models\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    /**
     * @return void
     *
     */
    public function testSchema(): void
    {
        $this->checkColumns(Response::TABLE, [
            Response::UUID,
        ]);
    }
}
