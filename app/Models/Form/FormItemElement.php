<?php

namespace App\Models\Form;

/**
 * @property string $uuid
 * @property string $title
 * @const string MODEL_TYPE
 */
interface FormItemElement
{
    public function getModelType(): string;
}
