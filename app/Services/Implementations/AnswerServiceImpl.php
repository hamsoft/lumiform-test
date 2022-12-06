<?php

namespace App\Services\Implementations;

use App\Models\Answer;
use App\Services\AnswerService;

class AnswerServiceImpl implements AnswerService
{

    public function addFormAnswers(string $formUuid, iterable $answersData): void
    {
        foreach ($answersData as $answerData) {
            $answerData[Answer::FORM_UUID] = $formUuid;
            Answer::create($answerData);
        }
    }
}
