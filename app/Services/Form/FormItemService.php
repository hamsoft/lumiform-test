<?php

namespace App\Services\Form;

use App\Models\Form\FormItemElement;

interface FormItemService
{
    public function getOrCreateFormItemElement(string $type, array $elementData): FormItemElement;

    public function getElementServiceByType(string $type): FormItemElementService;
}
