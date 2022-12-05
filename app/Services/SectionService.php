<?php

namespace App\Services;

use App\Models\Section;
use App\Services\Form\FormItemElementService;

interface SectionService extends FormItemElementService
{
    public function findByUuidOrCreate(iterable $data): Section;
}
