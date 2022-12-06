<?php

namespace App\Http\Controllers\Api;

use App\Collections\QuestionCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Questionnaire\StoreAnswersRequest;
use App\Models\Answer;
use App\Services\AnswerService;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;

class QuestionnaireController extends ApiController
{
    public function storeAnswers(
        StoreAnswersRequest $request,
        QuestionService $questionService,
        AnswerService $answerService
    ): JsonResponse {
        $formUuid = $request->getFormUuid();

        $questions = $questionService->getFormQuestionsByFormUuid($formUuid);

        $rules = $this->prepareQuestionRules($request->getAnswers(), $questions);

        $data = $this->validate($request, $rules, [], []);

        $answerService->addFormAnswers($formUuid, $data['answers']);

        return $this->storeResponse([
            'message' => trans('common.Successfully Stored')
        ]);
    }

    private function prepareQuestionRules(array $answers, QuestionCollection $questionCollection)
    {
        $rules = [];
        $answeredUuids = [];

        foreach ($answers as $key => $answer) {
            $answerUuid = $answer[Answer::QUESTION_UUID];
            $answeredUuids[] = $answerUuid;

            $question = $questionCollection->find($answerUuid);

            $rules['answers.' . $key . '.' . Answer::QUESTION_UUID] = 'required';
            $rules['answers.' . $key . '.notes'] = $question->notes_allowed ? 'string' : 'prohibited';
            $rules['answers.' . $key . '.issues'] = $question->issues_allowed ? 'array' : 'prohibited';
            $rules['answers.' . $key . '.photos'] = $question->photos_allowed ? 'mimes:jpg,bmp,png' : 'prohibited';
        }

        $unansweredRequiredQuestions = $questionCollection->getUuidsWhereRequiredAndUuidNotIn($answeredUuids);

        if ($unansweredRequiredQuestions->isNotEmpty()) {
            $rules['answers'] = function ($attribute, $value, $fail) use ($unansweredRequiredQuestions) {
                $fail(['answers.unanswered' => $unansweredRequiredQuestions->toArray()]);
            };
        }

        return $rules;
    }

}
