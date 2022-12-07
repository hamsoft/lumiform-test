<?php

namespace App\Services;

interface AnswerValidator
{
    public function validateFormAnswers(string $formUuid, array $data, string $prefixKey): array;
}
