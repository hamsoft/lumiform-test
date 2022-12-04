<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Form extends Model
{
    use HasFactory;

    public const TABLE = 'forms';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';

    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
    ];
}
