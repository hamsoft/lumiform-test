<?php

namespace App\Models;

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

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getElementType(): string
    {
        return self::MODEL_TYPE;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
