<?php

namespace Tests\Unit\Services\Implementations;

use App\Models\Question;
use App\Services\Implementations\QuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentQuestionServiceTest extends TestCase
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
        $service = $this->app->make(QuestionService::class);

        $data = Question::factory()->definition();
        if ($find) {
            $data[Question::UUID] = Question::create($data)->uuid;
        }

        $question = $service->findByUuidOrCreate($data);

        $this->assertModelExists($question);

        if (isset($data[Question::UUID])) {
            $this->assertEquals($data[Question::UUID], $question->uuid);
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
