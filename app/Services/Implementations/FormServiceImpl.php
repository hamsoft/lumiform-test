<?php

namespace App\Services\Implementations;

use App\Models\Form;
use App\Models\Form\FormItem;
use App\Services\Form\FormItemService as FormItemService;
use App\Services\FormService as FormServiceInterface;
use Illuminate\Support\Facades\DB;

class FormServiceImpl implements FormServiceInterface
{
    /**
     * @var \App\Services\Form\FormItemService
     */
    private FormItemService $formItemService;

    public function __construct(FormItemService $formItemService)
    {
        $this->formItemService = $formItemService;
    }

    public function getDefaultValidationRules(): array
    {
        return [
            Form::TITLE => 'required',
            Form::DESCRIPTION => 'required',
        ];
    }

    public function createForm(array $formData, array $itemsData = []): Form
    {
        return DB::transaction(function () use ($formData, $itemsData) {
            $form = Form::create($formData);

            $this->prepareFormItems($form, $itemsData);

            return $form;
        });
    }

    private function prepareFormItems(Form $form, array $itemsData, ?FormItem $parent = null): void
    {
        foreach ($itemsData as $itemData) {
            $element = $this->formItemService->getOrCreateFormItemElement($itemData['type'], $itemData);

            $formItem = $form->items()->create([
                FormItem::ELEMENT_UUID => $element->getUuid(),
                FormItem::ELEMENT_TYPE => $element->getElementType(),
                FormItem::PARENT_UUID => $parent?->uuid,
            ]);

            $this->prepareFormItems($form, $itemData['items'] ?? [], $formItem);;
        }
    }
}
