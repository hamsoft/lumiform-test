<?php

namespace Tests\Unit\Services\Implementations;

use App\Models\Page;
use App\Services\PageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentPageServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideFindByUuidOrCreateData
     *
     * @param bool $find
     *
     * @return void
     */
    public function testFindByUuidOrCreate(bool $find = false): void
    {
        $service = $this->app->make(PageService::class);

        $data = Page::factory()->definition();
        if ($find) {
            $expectedPage = Page::create($data);
            $data[Page::UUID] = $expectedPage->uuid;
        }

        $page = $service->findByUuidOrCreate($data);

        $this->assertModelExists($page);

        if (isset($data[Page::UUID])) {
            $this->assertEquals($data[Page::UUID], $page->uuid);
        }
    }

    public function provideFindByUuidOrCreateData()
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
