<?php

namespace App\Models\Form;

interface FormItemElement
{
    public function getUuid(): string;

    public function getElementType(): string;

    public function getTitle(): string;
}
