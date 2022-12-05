<?php

namespace App\Models\Form;

use App\Models\Model;
use App\Models\Page;
use App\Models\Question;
use App\Models\Section;

/**
 * @property string $element_uuid
 * @property string $element_type
 * @property \App\Models\Form\FormItemElement $element
 */
class FormItem extends Model
{
    public const TABLE = 'forms_items';

    public const FORM_UUID = 'form_uuid';
    public const ELEMENT_UUID = 'element_uuid';
    public const ELEMENT_TYPE = 'element_type';
    public const PARENT_UUID = 'parent_uuid';
    public const PARENT_TYPE = 'parent_type';

    protected $table = self::TABLE;

    protected $fillable = [
        self::FORM_UUID,
        self::ELEMENT_UUID,
        self::ELEMENT_TYPE,
        self::PARENT_UUID,
        self::PARENT_TYPE,
    ];

    public static function getAvailableTypes(): array
    {
        return [
            Question::MODEL_TYPE,
            Page::MODEL_TYPE,
            Section::MODEL_TYPE,
        ];
    }

    public function element()
    {
        return $this->morphTo('element', self::ELEMENT_TYPE, self::ELEMENT_UUID);
    }
}
