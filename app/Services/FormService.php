<?php

namespace App\Services;

use App\Models\Form;
use App\Services\ModelServices\WithDefaultValidationRules;

interface FormService
{
    public function createForm(array $formData, array $itemsData = []): Form;
}
