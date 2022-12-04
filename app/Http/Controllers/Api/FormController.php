<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Form\NewFormRequest;
use App\Services\FormService;

class FormController extends ApiController
{

    /**
     * Create new form
     *
     * @param \App\Http\Requests\Api\Form\NewFormRequest $request
     * @param \App\Services\FormService $formService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewForm(NewFormRequest $request, FormService $formService)
    {
        $form = $formService->createForm($request->validated());

        return $this->storeResponse([
            'uuid' => $form->uuid,
            'message' => 'Successfully Created',
        ]);
    }
}
