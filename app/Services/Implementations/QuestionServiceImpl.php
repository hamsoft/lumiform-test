<?php

namespace App\Services\Implementations;

use App\Collections\QuestionCollection;
use App\Models\Form\FormItem;
use App\Models\Question;
use App\Services\QuestionService as QuestionServiceInterface;
use Illuminate\Validation\Rule;

class QuestionServiceImpl implements QuestionServiceInterface
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

    public function getFormQuestionsByFormUuid(string $formUuid): QuestionCollection
    {
        $formItemsSubQuery = FormItem::query()
            ->whereElementTypeQuestion()
            ->whereFormUuid($formUuid)
            ->select(FormItem::ELEMENT_UUID)
            ->getQuery();

        return Question::query()->whereIn(Question::UUID, $formItemsSubQuery)->get();
    }
}
