<?php

namespace Tests\Feature\Models;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    public function testSchema()
    {
        $this->checkColumns(Answer::TABLE, [
            Answer::UUID,
            Answer::FORM_UUID,
            Answer::QUESTION_UUID,
        ]);
    }
}
