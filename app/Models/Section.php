<?php

namespace App\Models;

use App\Models\Form\FormItemElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $title
 * @property boolean $repeat
 * @property integer $weight
 * @property boolean $required
 */
class Section extends Model implements FormItemElement
{
    use HasFactory;

    public const TABLE = 'sections';
    public const MODEL_TYPE = 'section';

    public const TITLE = 'title';
    public const REPEAT = 'repeat';
    public const WEIGHT = 'weight';
    public const REQUIRED = 'required';

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getElementType(): string
    {
        return self::MODEL_TYPE;
    }

    protected $fillable = [
        self::TITLE,
        self::REPEAT,
        self::WEIGHT,
        self::REQUIRED,
    ];

    public function getTitle(): string
    {
        return $this->title;
    }
}
