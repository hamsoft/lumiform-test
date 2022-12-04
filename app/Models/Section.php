<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    const TABLE = 'sections';

    const TITLE = 'title';
    const REPEAT = 'repeat';
    const WEIGHT = 'weight';
    const REQUIRED = 'required';
}
