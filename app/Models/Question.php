<?php

namespace App\Models;

use App\Collections\QuestionCollection;
use App\Models\Form\FormItem;
use App\Models\Form\FormItemElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $title
 * @property string $image_id
 * @property boolean $negative
 * @property boolean $notes_allowed
 * @property boolean $photos_allowed
 * @property boolean $issues_allowed
 * @property boolean $responded
 * @property boolean $required
 * @property string $response_type
 * @method QuestionCollection get()
 */
class Question extends Model implements FormItemElement
{
    use HasFactory;

    public const TABLE = 'questions';
    public const MODEL_TYPE = 'question';

    public const TITLE = 'title';
    public const IMAGE_ID = 'image_id';
    public const NEGATIVE = 'negative';
    public const NOTES_ALLOWED = 'notes_allowed';
    public const PHOTOS_ALLOWED = 'photos_allowed';
    public const ISSUES_ALLOWED = 'issues_allowed';
    public const RESPONDED = 'responded';
    public const REQUIRED = 'required';
    public const RESPONSE_TYPE = 'response_type';

    public const RELATION_FORM_ITEM = 'formItems';

    public const RESPONSE_TYPES = ['list'];

    protected $casts = [
        self::NEGATIVE => 'boolean',
        self::NOTES_ALLOWED => 'boolean',
        self::PHOTOS_ALLOWED => 'boolean',
        self::ISSUES_ALLOWED => 'boolean',
        self::RESPONDED => 'boolean',
        self::REQUIRED => 'boolean',
    ];

    protected $fillable = [
        self::TITLE,
        self::IMAGE_ID,
        self::NEGATIVE,
        self::NOTES_ALLOWED,
        self::PHOTOS_ALLOWED,
        self::ISSUES_ALLOWED,
        self::RESPONDED,
        self::REQUIRED,
        self::RESPONSE_TYPE,
    ];

    public function getModelType(): string
    {
        return self::MODEL_TYPE;
    }

    public function formItems()
    {
        return $this->morphMany(
            FormItem::class,
            FormItem::RELATION_ELEMENT,
            FormItem::ELEMENT_TYPE,
            FormItem::ELEMENT_UUID
        );
    }

    public function newCollection(array $models = []): QuestionCollection
    {
        return new QuestionCollection($models);
    }
}
