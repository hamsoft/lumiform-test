<?php

namespace Tests\Feature\Models\Form;

use App\Models\Form\FormItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\ColumnsChecker;
use Tests\TestCase;

class FormItemTest extends TestCase
{
    use RefreshDatabase, ColumnsChecker;

    public function testSchema()
    {
        $this->checkColumns(FormItem::TABLE, [
            FormItem::UUID,
            FormItem::ELEMENT_UUID,
            FormItem::ELEMENT_TYPE,
            FormItem::PARENT_UUID,
            FormItem::PARENT_TYPE,
        ]);
    }
}
