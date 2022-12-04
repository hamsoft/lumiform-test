<?php

namespace Tests\Feature\Models;

use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    public function testSchema()
    {
        $this->checkColumns(Form::TABLE, [
            Form::UUID,
            Form::TITLE,
            Form::DESCRIPTION,
        ]);
    }

    public function testCreate()
    {
        $form = Form::factory()->create();

        $this->assertModelExists($form);
    }
}
