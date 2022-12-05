<?php

namespace App\Services\Implementations;

use App\Models\Question;
use App\Services\QuestionService as QuestionServiceInterface;
use Illuminate\Validation\Rule;

class QuestionService implements QuestionServiceInterface
{
    public function findByUuidOrCreate(iterable $data): Question
    {
        $question = Question::query()->findOrNew($data[Question::UUID] ?? null);

        $question->fill((array)$data)->save();

        return $question;
    }

    public function getDefaultValidationRules(): array
    {
        return [
            Question::TITLE => 'required',
            Question::IMAGE_ID => 'string',
            Question::NEGATIVE => 'boolean',
            Question::NOTES_ALLOWED => 'boolean',
            Question::PHOTOS_ALLOWED => 'boolean',
            Question::ISSUES_ALLOWED => 'boolean',
            Question::RESPONDED => 'boolean',
            Question::REQUIRED => 'boolean',
            Question::RESPONSE_TYPE => [
                'required',
                Rule::in(Question::RESPONSE_TYPES),
            ],
        ];
    }
}
