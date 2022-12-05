<?php

namespace App\Services;

use App\Models\Page;
use App\Services\Form\FormItemElementService;

interface PageService extends FormItemElementService
{
    public function findByUuidOrCreate(iterable $data): Page;
}
