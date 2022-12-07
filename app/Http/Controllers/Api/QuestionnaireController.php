<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Questionnaire\StoreAnswersRequest;
use App\Services\AnswerService;
use App\Services\AnswerValidator;
use Illuminate\Http\JsonResponse;

class QuestionnaireController extends ApiController
{
    public function storeAnswers(
        StoreAnswersRequest $request,
        AnswerValidator $answerValidator,
        AnswerService $answerService
    ): JsonResponse {
        $formUuid = $request->getFormUuid();

        $answersData = $answerValidator->validateFormAnswers(
            $formUuid,
            $request->all(),
            StoreAnswersRequest::ANSWERS
        );

        $answerService->addFormAnswers($formUuid, $answersData[StoreAnswersRequest::ANSWERS]);

        return $this->storeResponse([
            'message' => trans('common.Successfully Stored')
        ]);
    }

}
