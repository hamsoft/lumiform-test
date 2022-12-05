<?php

namespace Tests\Feature\Models;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    /**
     * @return void
     */
    public function testSchema(): void
    {
        $this->checkColumns(Section::TABLE, [
            Section::UUID,
            Section::TITLE,
            Section::REPEAT,
            Section::WEIGHT,
            Section::REQUIRED,
        ]);
    }
}
