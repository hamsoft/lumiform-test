<?php

namespace App\Models;

use App\Models\Form\FormItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property string $title
 * @property string $description
 * @property \Illuminate\Database\Eloquent\Collection<FormItem> $items
 * @property \Illuminate\Database\Eloquent\Collection<FormItem> $itemsWithElements
 */
class Form extends Model
{
    use HasFactory;

    public const TABLE = 'forms';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';

    public const MODEL_TYPE = 'form';

    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FormItem::class, FormItem::FORM_UUID);
    }

    public function itemsWithElements(): HasMany
    {
        return $this->items()->with(FormItem::RELATION_ELEMENT);
    }
}
