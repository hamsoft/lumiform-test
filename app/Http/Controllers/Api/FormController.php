<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Form\NewFormRequest;
use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Http\JsonResponse;

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
    public function createNewForm(NewFormRequest $request, FormService $formService): JsonResponse
    {
        $form = $formService->createForm($request->validated(), $request->get('items', []));

        return $this->storeResponse([
            'uuid' => $form->uuid,
            'message' => 'Successfully Created',
        ]);
    }

    /**
     * Get form by uuid
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForm(string $id): JsonResponse
    {
        $form = Form::findOrFail($id);

        return $this->response(new FormResource($form));
    }
}
