<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    public const TABLE = 'pages';
    const MODEL_TYPE = 'page';

    public const TITLE = 'title';
}
