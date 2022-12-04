<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    public const TABLE = 'questions';

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
}
