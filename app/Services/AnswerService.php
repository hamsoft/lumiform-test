<?php

namespace App\Services;

interface AnswerService
{
    public function addFormAnswers(string $formUuid, iterable $answersData): void;
}
