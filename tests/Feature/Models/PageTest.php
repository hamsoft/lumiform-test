<?php

namespace Tests\Feature\Models;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    /**
     * @return void
     */
    public function testSchema(): void
    {
        $this->checkColumns(Page::TABLE, [
            Page::UUID,
            Page::TITLE,
        ]);
    }
}
