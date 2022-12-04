<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    public const TABLE = 'sections';
    public const MODEL_TYPE = 'section';

    public const TITLE = 'title';
    public const REPEAT = 'repeat';
    public const WEIGHT = 'weight';
    public const REQUIRED = 'required';
}
