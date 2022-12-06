<?php

namespace App\Models;

class Answer extends Model
{
    const TABLE = 'answers';
    const QUESTION_UUID = 'question_uuid';
    const FORM_UUID = 'form_uuid';

    protected $fillable = [
        self::FORM_UUID,
        self::QUESTION_UUID,
    ];
}
