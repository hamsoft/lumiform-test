<?php

namespace App\Services;

use App\Models\Form;

class FormService
{

    public function createForm(mixed $formData): Form
    {
        return Form::create($formData);
    }
}
