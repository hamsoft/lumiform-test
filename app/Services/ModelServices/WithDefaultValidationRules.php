<?php

namespace App\Services\ModelServices;

interface WithDefaultValidationRules
{
    public function getDefaultValidationRules(): array;
}
