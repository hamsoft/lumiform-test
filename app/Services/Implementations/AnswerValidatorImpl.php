<?php

namespace App\Services\Implementations;

use App\Collections\QuestionCollection;
use App\Models\Answer;
use App\Services\AnswerValidator;
use App\Services\QuestionService;
use Illuminate\Validation\Factory as ValidationFactory;

class AnswerValidatorImpl implements AnswerValidator
{
    private QuestionService $questionService;
    private ValidationFactory $validator;
    private array $attributes = [];
    private array $messages = [];


    public function __construct(QuestionService $questionService, ValidationFactory $validator)
    {
        $this->questionService = $questionService;
        $this->validator = $validator;
    }

    public function validateFormAnswers(string $formUuid, array $data, string $prefixKey = 'answers'): array
    {
        $questions = $this->questionService->getFormQuestionsByFormUuid($formUuid);

        $rules = $this->prepareQuestionRules($data[$prefixKey], $questions, $prefixKey);

        return $this->validator->validate($data, $rules, $this->messages, $this->attributes);
    }

    private function prepareQuestionRules(array $answers, QuestionCollection $questionCollection, $prefixKey): array
    {
        $rules = [];
        $answeredUuids = [];

        foreach ($answers as $key => $answer) {
            $answerUuid = $answer[Answer::QUESTION_UUID];
            $answeredUuids[] = $answerUuid;

            $question = $questionCollection->find($answerUuid);

            if(!$question) {
                continue;
            }

            $rules[$prefixKey . '.' . $key . '.' . Answer::QUESTION_UUID] = 'required|string';
            $rules[$prefixKey . '.' . $key . '.notes'] = $question->notes_allowed ? 'string' : 'prohibited';
            $rules[$prefixKey . '.' . $key . '.issues'] = $question->issues_allowed ? 'array' : 'prohibited';
            $rules[$prefixKey . '.' . $key . '.photos'] = $question->photos_allowed ? 'mimes:jpg,bmp,png' : 'prohibited';
        }

        $unansweredRequiredQuestions = $questionCollection->getUuidsWhereRequiredAndUuidNotIn($answeredUuids);

        if ($unansweredRequiredQuestions->isNotEmpty()) {
            $rules[$prefixKey] = function ($attribute, $value, $fail) use ($unansweredRequiredQuestions, $prefixKey) {
                $fail([
                    $prefixKey . '.required_questions' =>  $unansweredRequiredQuestions->toArray(),
                ]);
            };
        }

        return $rules;
    }
}
