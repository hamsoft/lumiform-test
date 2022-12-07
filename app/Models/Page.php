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

    protected $fillable = [
        self::TITLE,
    ];

    public function getModelType(): string
    {
        return self::MODEL_TYPE;
    }
}
