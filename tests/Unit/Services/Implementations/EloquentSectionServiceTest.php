<?php

namespace Tests\Unit\Services\Implementations;

use App\Models\Section;
use App\Services\Implementations\SectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentSectionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideFindByUuidOrCreateData
     *
     * @param bool $find
     *
     * @return void
     */
    public function testFindByUuidOrCreate(bool $find): void
    {
        $service = $this->app->make(SectionService::class);

        $data = Section::factory()->definition();
        if ($find) {
            $expectedSection = Section::create($data);
            $data[Section::UUID] = $expectedSection->uuid;
        }

        $section = $service->findByUuidOrCreate($data);

        $this->assertModelExists($section);

        if (isset($data[Section::UUID])) {
            $this->assertEquals($data[Section::UUID], $section->uuid);
        }
    }

    public function provideFindByUuidOrCreateData(): array
    {
        return [
            'create' => [
                'find' => false,
            ],
            'find' => [
                'find' => true,
            ],
        ];
    }
}
