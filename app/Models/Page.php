<?php

namespace App\Models;

use App\Models\Form\FormItemElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $title
 */
class Page extends Model implements FormItemElement
{
    use HasFactory;

    public const TABLE = 'pages';
    public const MODEL_TYPE = 'page';

    public const TITLE = 'title';

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
    ];

    public function getTitle(): string
    {
        return $this->title;
    }
}
