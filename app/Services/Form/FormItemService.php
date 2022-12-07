<?php

namespace App\Services\Form;

use App\Models\Model;

interface FormItemService
{
    public function getOrCreateFormItemElement(string $type, array $elementData): Model;

    public function getElementServiceByType(string $type): FormItemElementService;
}
