<?php

namespace Tests\Feature\Models;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    /**
     * @return void
     *
     */
    public function testSchema(): void
    {
        $this->checkColumns(Question::TABLE, [
            Question::UUID,
            Question::TITLE,
            Question::IMAGE_ID,
            Question::NEGATIVE,
            Question::NOTES_ALLOWED,
            Question::PHOTOS_ALLOWED,
            Question::ISSUES_ALLOWED,
            Question::RESPONDED,
            Question::REQUIRED,
            Question::RESPONSE_TYPE,
        ]);
    }
}
