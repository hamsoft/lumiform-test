<?php

namespace App\Services\Implementations;

use App\Models\Page;
use App\Services\PageService as PageServiceInterface;

class PageServiceImpl implements PageServiceInterface
{
    public function findByUuidOrCreate(iterable $data): Page
    {
        $page = Page::query()->findOrNew($data[Page::UUID] ?? null);

        $page->fill((array)$data)->save();

        return $page;
    }

    public function getDefaultValidationRules(): array
    {
        return [
            'title' => 'required|string',
        ];
    }
}
