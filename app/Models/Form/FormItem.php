<?php

namespace App\Models\Form;

use App\Models\Form;
use App\Models\Model;
use App\Models\Page;
use App\Models\Question;
use App\Models\Section;

/**
 * @property string $element_uuid
 * @property string $element_type
 * @property \App\Models\Form\FormItemElement $element
 * @method |static whereElementTypeQuestion()
 * @method |static whereFormUuid($uuid)
 */
class FormItem extends Model
{
    public const TABLE = 'forms_items';

    public const FORM_UUID = 'form_uuid';
    public const ELEMENT_UUID = 'element_uuid';
    public const ELEMENT_TYPE = 'element_type';
    public const PARENT_UUID = 'parent_uuid';
    public const PARENT_TYPE = 'parent_type';
    public const RELATION_ELEMENT = 'element';

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

    public function form()
    {
        return $this->belongsTo(Form::class, self::FORM_UUID, Form::UUID);
    }

    public function element()
    {
        return $this->morphTo(self::RELATION_ELEMENT, self::ELEMENT_TYPE, self::ELEMENT_UUID);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, self::PARENT_UUID, self::UUID);
    }

    public function scopeWhereElementTypeQuestion($query)
    {
        return $query->where(self::ELEMENT_TYPE, Question::MODEL_TYPE);
    }
}
