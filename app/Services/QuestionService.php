<?php

namespace App\Services;

use App\Collections\QuestionCollection;
use App\Models\Question;
use App\Services\Form\FormItemElementService;

interface QuestionService extends FormItemElementService
{
    public function findByUuidOrCreate(iterable $data): Question;

    public function getFormQuestionsByFormUuid(string $formUuid): QuestionCollection;
}
