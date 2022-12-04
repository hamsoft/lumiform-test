<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    public const TABLE = 'pages';

    public const TITLE = 'title';
}
