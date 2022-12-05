<?php

namespace App\Services;

use App\Models\Question;
use App\Services\Form\FormItemElementService;

interface QuestionService extends FormItemElementService
{
    public function findByUuidOrCreate(iterable $data): Question;
}
