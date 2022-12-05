<?php

namespace App\Services\Form;

use App\Models\Form\FormItemElement;
use App\Services\ModelServices\WithDefaultValidationRules;

interface FormItemElementService extends WithDefaultValidationRules
{
    public function findByUuidOrCreate(iterable $data): FormItemElement;
}
